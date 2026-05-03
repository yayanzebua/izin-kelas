<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $users = User::withCount('izin')->orderBy('role')->orderBy('name')->paginate(20);
        $stats = [
            'total_users' => User::count(),
            'siswa' => User::where('role', 'siswa')->count(),
            'guru' => User::where('role', 'guru')->count(),
            'total_izin' => Izin::count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
