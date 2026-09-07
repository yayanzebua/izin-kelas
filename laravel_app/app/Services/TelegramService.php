<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function sendMessage(string $chatId, string $message, string $parseMode = 'HTML', array $replyMarkup = []): array|bool
    {
        $token = config('telegram.bot_token');
        if (!$token) {
            Log::warning('Telegram bot token not configured di env');
            return false;
        }

        // Telegram bot token format biasanya: <digits>:<secret>
        // Jika formatnya tidak benar, Telegram API sering membalas 404 Not Found.
        if (!str_contains($token, ':')) {
            Log::error('Telegram bot token format invalid (expected ":" in token).', [
                'bot_token_length' => strlen($token),
            ]);

            return false;
        }

        try {
            Log::debug('5. Mengirim request sendMessage ke Telegram API', [
                'chat_id' => $chatId,
                'text' => $message,
                'bot_token_length' => strlen($token)
            ]);

            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ];

            if (!empty($replyMarkup)) {
                $payload['reply_markup'] = json_encode($replyMarkup);
            }

            $response = Http::timeout(10)->withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", $payload);

            if ($response->failed()) {
                $body = $response->json() ?? $response->body();
                Log::error('6. Telegram send failed (Response error dari Telegram)', [
                    'chat_id' => $chatId,
                    'status' => $response->status(),
                    'body' => $body,
                ]);

                if (
                    $response->status() === 404
                    && is_array($body)
                    && (($body['description'] ?? null) === 'Not Found')
                ) {
                    Log::error('Telegram API returned 404 Not Found: kemungkinan TELEGRAM_BOT_TOKEN salah/invalid.', [
                        'chat_id' => $chatId,
                    ]);
                }

                return false;
            }

            Log::debug('6. Response Telegram API berhasil diterima', ['response' => $response->json()]);

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('6. Telegram send exception (Gagal melakukan HTTP request)', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    public function editMessageText(string $chatId, string $messageId, string $text, string $parseMode = 'HTML', array $replyMarkup = []): bool
    {
        $token = config('telegram.bot_token');
        if (!$token) return false;

        try {
            $payload = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => $parseMode,
            ];

            if (!empty($replyMarkup)) {
                $payload['reply_markup'] = json_encode($replyMarkup);
            }

            $response = Http::timeout(10)->withoutVerifying()->post("https://api.telegram.org/bot{$token}/editMessageText", $payload);
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Telegram editMessageText exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
