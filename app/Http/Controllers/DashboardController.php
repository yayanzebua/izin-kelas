<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match($user->role) {
            'guru' => redirect()->route('guru.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }

    public function siswa()
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            return $this->redirectByRole($user);
        }

        $stats = [
            'total' => Izin::where('user_id', $user->id)->count(),
            'menunggu' => Izin::where('user_id', $user->id)->where('status', 'menunggu')->count(),
            'disetujui' => Izin::where('user_id', $user->id)->where('status', 'disetujui')->count(),
            'ditolak' => Izin::where('user_id', $user->id)->where('status', 'ditolak')->count(),
        ];

        $recentIzin = Izin::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.siswa', compact('stats', 'recentIzin'));
    }

    public function guru()
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            return redirect()->route('siswa.dashboard');
        }

        $stats = [
            'total' => Izin::count(),
            'menunggu' => Izin::where('status', 'menunggu')->count(),
            'disetujui' => Izin::where('status', 'disetujui')->count(),
            'ditolak' => Izin::where('status', 'ditolak')->count(),
        ];

        $pendingIzin = Izin::with('user')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.guru', compact('stats', 'pendingIzin'));
    }

    public function admin()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return $this->redirectByRole($user);
        }

        return redirect()->route('admin.users.index');
    }

    private function redirectByRole($user)
    {
        return match($user->role) {
            'guru' => redirect()->route('guru.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }
}
