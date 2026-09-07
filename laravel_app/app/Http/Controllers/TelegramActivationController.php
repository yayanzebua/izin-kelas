<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TelegramActivationController extends Controller
{
    public function activate(): RedirectResponse
    {
        $user = Auth::user();
        $botUsername = config('telegram.bot_username');

        if (!$botUsername) {
            return back()->with('error', 'Bot Telegram belum dikonfigurasi.');
        }

        $token = Str::random(32);
        $user->update([
            'telegram_activation_token' => $token,
        ]);

        $url = "https://t.me/{$botUsername}?start={$token}";

        return redirect()->away($url);
    }
}
