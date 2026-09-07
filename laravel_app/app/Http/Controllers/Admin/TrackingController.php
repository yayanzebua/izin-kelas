<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $threshold = now()->subMinutes(3);
            if ($request->status === 'online') {
                $query->where('last_seen_at', '>=', $threshold);
            } elseif ($request->status === 'offline') {
                $query->where(function($q) use ($threshold) {
                    $q->whereNull('last_seen_at')
                      ->orWhere('last_seen_at', '<', $threshold);
                });
            }
        }

        // Sort by last_seen_at descending to show active users first
        $students = $query->orderBy('last_seen_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.tracking.index', compact('students'));
    }

    public function toggleAccess(Request $request, User $user)
    {
        // Prevent modifying non-siswa accounts via this endpoint
        if ($user->role !== 'siswa') {
            return response()->json(['success' => false, 'message' => 'Hanya akun mahasiswa yang dapat dimodifikasi.'], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => 'Status akses berhasil diperbarui.'
        ]);
    }
}
