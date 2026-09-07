<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $stats = Cache::remember('admin_dashboard_stats', 3600, function () {
                $permissionStats = Permission::selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN type = 'sakit' THEN 1 ELSE 0 END) as sakit,
                    SUM(CASE WHEN type = 'izin' THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
                ")->first();

                return [
                    'total' => (int) ($permissionStats->total ?? 0),
                    'sakit' => (int) ($permissionStats->sakit ?? 0),
                    'izin' => (int) ($permissionStats->izin ?? 0),
                    'pending' => (int) ($permissionStats->pending ?? 0),
                    'students' => \App\Models\User::where('role', 'siswa')->count(),
                    'subjects' => ClassRoom::count(),
                ];
            });

            $recentPermissions = Permission::with(['user', 'classRoom'])->latest()->take(5)->get();
            return view('admin.dashboard', compact('stats', 'recentPermissions'));
        }

        $permissionsQuery = Permission::where('user_id', $user->id);
        
        $now = now();
        $startOfWeek = $now->copy()->startOfWeek()->format('Y-m-d');
        $endOfWeek = $now->copy()->endOfWeek()->format('Y-m-d');

        $siswaStats = (clone $permissionsQuery)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN MONTH(date) = ? AND YEAR(date) = ? THEN 1 ELSE 0 END) as thisMonth,
            SUM(CASE WHEN date BETWEEN ? AND ? THEN 1 ELSE 0 END) as thisWeek,
            MAX(date) as lastPermissionDate
        ", [$now->month, $now->year, $startOfWeek, $endOfWeek])->first();

        $totalPermissions = (int) ($siswaStats->total ?? 0);
        $thisMonth = (int) ($siswaStats->thisMonth ?? 0);
        $thisWeek = (int) ($siswaStats->thisWeek ?? 0);
        $lastPermissionDate = $siswaStats->lastPermissionDate;
        
        $permissions = (clone $permissionsQuery)->with('classRoom')
            ->latest('created_at')
            ->paginate(5);
            
        $subjects = $user->subjects;
        return view('siswa.dashboard', compact(
            'permissions', 'subjects', 'totalPermissions', 
            'thisMonth', 'thisWeek', 'lastPermissionDate'
        ));
    }
}
