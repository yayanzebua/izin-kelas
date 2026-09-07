<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => ['required'],
            'password' => ['required'],
        ]);

        // Attempt login with Name (Username), Email OR NIM
        $credentials_email = ['email' => $request->login_id, 'password' => $request->password];
        $credentials_name = ['name' => $request->login_id, 'password' => $request->password];
        $credentials_nim = ['nim' => $request->login_id, 'password' => $request->password];

        if (Auth::attempt($credentials_email) || Auth::attempt($credentials_name) || Auth::attempt($credentials_nim)) {
            $user = Auth::user();

            // Cek apakah akun mahasiswa sedang dinonaktifkan (di-banned/dicabut hak loginnya)
            if ($user->role === 'siswa' && !$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'login_id' => 'Hak login Anda sedang dicabut oleh admin. Silakan hubungi admin untuk info lebih lanjut.',
                ])->onlyInput('login_id');
            }

            // Segera perbarui status online sesaat setelah login berhasil
            $user->update(['last_seen_at' => now()]);

            $request->session()->regenerate();

            if ($user->telegram_chat_id) {
                $time = now()->translatedFormat('d F Y, H:i');
                $message = "🔐 <b>Notifikasi Login</b>\n\n" .
                    "Halo, <b>{$user->name}</b>!\n" .
                    "Akun Anda baru saja login ke aplikasi <b>Izin Kelas 018</b>.\n\n" .
                    "📅 <b>Waktu:</b> {$time} WIB\n" .
                    "📱 <b>Perangkat:</b> " . $request->userAgent() . "\n\n" .
                    "<i>Jika ini bukan Anda, silakan hubungi admin untuk keamanan akun.</i>";

                \App\Jobs\SendLoginNotification::dispatch($user->telegram_chat_id, $message);
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login_id' => 'ID (Email/NIM) atau password salah.',
        ])->onlyInput('login_id');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
