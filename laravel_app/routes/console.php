<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('telegram:reset {nim : NIM user yang akan di-reset Telegram-nya}', function (string $nim) {
    $user = User::where('nim', $nim)->first();

    if (!$user) {
        $this->error("User dengan NIM {$nim} tidak ditemukan.");
        return 1;
    }

    $wasConnected = (bool) $user->telegram_chat_id;

    $user->forceFill([
        'telegram_chat_id' => null,
        'telegram_activation_token' => null,
    ])->save();

    $status = $wasConnected ? 'DISCONNECT (reset berhasil)' : 'sudah dalam kondisi belum terhubung';
    $this->info("Telegram untuk user {$user->name} ({$user->nim}) => {$status}.");

    return 0;
})->purpose('Reset (disconnect) Telegram user berdasarkan NIM');
