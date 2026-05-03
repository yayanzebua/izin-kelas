<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'guru' => redirect()->route('guru.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }
    return redirect()->route('login');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/siswa', [DashboardController::class, 'siswa'])->name('siswa.dashboard');
    Route::get('/dashboard/guru', [DashboardController::class, 'guru'])->name('guru.dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Izin routes
    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/baru', [IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::get('/izin/semua', [IzinController::class, 'semua'])->name('izin.semua');
    Route::get('/izin/{izin}', [IzinController::class, 'show'])->name('izin.show');
    Route::post('/izin/{izin}/setujui', [IzinController::class, 'approve'])->name('izin.approve');
    Route::post('/izin/{izin}/tolak', [IzinController::class, 'reject'])->name('izin.reject');

    // Admin routes
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
