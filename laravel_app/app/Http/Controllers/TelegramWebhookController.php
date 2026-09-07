<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        try {
            Log::debug('1. Webhook Telegram diterima', ['payload' => $request->all()]);

            $callbackQuery = $request->input('callback_query');
            if ($callbackQuery) {
                return $this->handleCallbackQuery($callbackQuery);
            }

            $message = $request->input('message');
            if (!$message || !isset($message['text'], $message['chat']['id'])) {
                Log::debug('2. Payload Telegram tidak valid atau bukan pesan teks');
                return response()->noContent();
            }

            $text = trim($message['text']);
            $chatId = (string) $message['chat']['id'];
            
            Log::debug('3. Data pesan dan chat_id terbaca', ['chat_id' => $chatId, 'text' => $text]);

            if (!str_starts_with($text, '/start')) {
                Log::debug('4. Pesan bukan perintah /start, diabaikan');
                return response()->noContent();
            }

            $parts = explode(' ', $text, 2);
            $token = $parts[1] ?? null;

            if (!$token) {
                Log::info('4. Token aktivasi tidak ditemukan pada pesan /start', ['chat_id' => $chatId]);
                app(TelegramService::class)->sendMessage(
                    $chatId,
                    'Aktivasi belum terhubung. Silakan klik tombol "Aktifkan Telegram" di aplikasi untuk mendapatkan link aktivasi.'
                );
                return response()->noContent();
            }

            $user = User::where('telegram_activation_token', $token)->first();
            if (!$user) {
                Log::info('4. Token aktivasi tidak valid/ditemukan di database', ['token' => $token, 'chat_id' => $chatId]);
                app(TelegramService::class)->sendMessage(
                    $chatId,
                    'Kode aktivasi tidak valid. Silakan klik tombol "Aktifkan Telegram" di aplikasi untuk membuat kode baru.'
                );
                return response()->noContent();
            }
            
            $user->update([
                'telegram_chat_id' => $chatId,
                'telegram_activation_token' => null,
            ]);

            Log::info('5. Akun berhasil terhubung dengan Telegram', ['user_id' => $user->id, 'chat_id' => $chatId]);

            app(TelegramService::class)->sendMessage(
                $chatId,
                "✅ <b>Aktivasi Berhasil!</b>\n\n" .
                "Halo, <b>{$user->name}</b>. Akun Anda sekarang telah resmi terhubung dengan layanan notifikasi Telegram <b>Izin Kelas 018</b>.\n\n" .
                "🚀 <b>Pantau terus Telegram Anda!</b>\n" .
                "Setiap kali Anda berhasil mengajukan izin baru atau ada pembaruan status, notifikasi otomatis akan langsung dikirimkan ke sini.\n\n" .
                "<i>Terima kasih telah menggunakan sistem Izin Kelas 018.</i>"
            );

            return response()->noContent();
        } catch (\Exception $e) {
            Log::error('Error pada TelegramWebhookController', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->noContent();
        }
    }

    private function handleCallbackQuery(array $callbackQuery): Response
    {
        $data = $callbackQuery['data'] ?? '';
        $message = $callbackQuery['message'] ?? null;
        
        if (!$message || !isset($message['chat']['id'], $message['message_id'])) {
            return response()->noContent();
        }

        $chatId = (string) $message['chat']['id'];
        $messageId = (string) $message['message_id'];
        
        Log::debug('Callback query diterima', ['data' => $data, 'chat_id' => $chatId]);

        if (str_starts_with($data, 'approve_contact_') || str_starts_with($data, 'reject_contact_')) {
            $isApprove = str_starts_with($data, 'approve_contact_');
            $requestId = str_replace(['approve_contact_', 'reject_contact_'], '', $data);
            
            $contactRequest = \App\Models\ContactRequest::with(['user', 'lecturer'])->find($requestId);
            
            if (!$contactRequest) {
                app(TelegramService::class)->editMessageText(
                    $chatId,
                    $messageId,
                    "⚠️ Permintaan kontak tidak ditemukan."
                );
                return response()->noContent();
            }

            if ($contactRequest->status !== 'pending') {
                $statusText = $contactRequest->status === 'approved' ? 'Disetujui' : 'Ditolak';
                app(TelegramService::class)->editMessageText(
                    $chatId,
                    $messageId,
                    "⚠️ Permintaan ini sudah diproses. (Status: {$statusText})"
                );
                return response()->noContent();
            }

            // Update status
            $contactRequest->update([
                'status' => $isApprove ? 'approved' : 'rejected'
            ]);

            // Edit telegram message
            $adminAction = $isApprove ? "✅ <b>DISETUJUI</b>" : "❌ <b>DITOLAK</b>";
            
            $newText = "🚨 <b>Notifikasi Kontak Dosen</b>\n\n" .
                "Mahasiswa <b>{$contactRequest->user->name}</b> meminta persetujuan untuk menghubungi Dosen <b>{$contactRequest->lecturer->name}</b> via WhatsApp.\n\n" .
                "Status: {$adminAction}";

            app(TelegramService::class)->editMessageText(
                $chatId,
                $messageId,
                $newText
            );
        }

        // Telegram requires answering callback queries to remove the loading state on the button
        $token = config('telegram.bot_token');
        if ($token && isset($callbackQuery['id'])) {
            \Illuminate\Support\Facades\Http::timeout(5)->withoutVerifying()->post("https://api.telegram.org/bot{$token}/answerCallbackQuery", [
                'callback_query_id' => $callbackQuery['id'],
            ]);
        }

        return response()->noContent();
    }
}
