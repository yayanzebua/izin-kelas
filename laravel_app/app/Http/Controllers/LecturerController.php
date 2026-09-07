<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::all();
        
        // Urutan mata kuliah yang diinginkan
        $order = [
            'JARINGAN NIRKABEL',
            'KEAMANAN KOMPUTER',
            'PEMROGRAMAN WEB II',
            'KECAKAPAN ANTAR PERSONAL',
            'ETIKA PROFESI',
            'ARSITEKTUR DAN ORGANISASI KOMPUTER',
            'TESTING DAN QA PERANGKAT LUNAK',
            'MANAJEMEN PROYEK INFORMATIKA'
        ];

        $lecturers = $lecturers->sortBy(function ($lecturer) use ($order) {
            $subject = strtoupper(trim($lecturer->subject));
            $pos = array_search($subject, $order);
            return $pos === false ? 999 : $pos;
        });

        return view('siswa.lecturers.index', compact('lecturers'));
    }

    public function contact(Request $request, Lecturer $lecturer)
    {
        $user = Auth::user();

        // Check if there's an existing pending request
        $existingRequest = \App\Models\ContactRequest::where('user_id', $user->id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['status' => 'pending']);
        }

        // Create new pending request
        $contactRequest = \App\Models\ContactRequest::create([
            'user_id' => $user->id,
            'lecturer_id' => $lecturer->id,
            'status' => 'pending',
        ]);

        // Broadcast to all admins who have telegram_chat_id
        $admins = User::where('role', 'admin')->whereNotNull('telegram_chat_id')->get();

        if ($admins->count() > 0) {
            $device = $request->header('User-Agent');
            $ip = $request->ip();
            $time = now()->format('d M Y, H:i:s');
            
            $message = "🚨 <b>Notifikasi Kontak Dosen</b>\n\n" .
                "Mahasiswa <b>{$user->name}</b> sedang meminta persetujuan untuk menghubungi Dosen <b>{$lecturer->name}</b> via WhatsApp.\n\n" .
                "Mata Kuliah: " . ($lecturer->subject ?? '-') . "\n" .
                "Waktu: {$time}\n" .
                "IP Address: {$ip}\n" .
                "Perangkat: {$device}";

            $replyMarkup = [
                'inline_keyboard' => [
                    [
                        ['text' => '✅ Setujui', 'callback_data' => 'approve_contact_' . $contactRequest->id],
                        ['text' => '❌ Tolak', 'callback_data' => 'reject_contact_' . $contactRequest->id],
                    ]
                ]
            ];

            $telegramService = app(TelegramService::class);
            
            foreach ($admins as $admin) {
                $response = $telegramService->sendMessage($admin->telegram_chat_id, $message, 'HTML', $replyMarkup);
                
                // Save the message ID so we can edit it later
                if (is_array($response) && isset($response['ok']) && $response['ok']) {
                    $contactRequest->update([
                        'telegram_message_id' => $response['result']['message_id']
                    ]);
                }
            }
        }

        return response()->json(['status' => 'pending']);
    }

    public function checkContactStatus(Lecturer $lecturer)
    {
        $user = Auth::user();
        
        $request = \App\Models\ContactRequest::where('user_id', $user->id)
            ->where('lecturer_id', $lecturer->id)
            ->orderBy('created_at', 'desc')
            ->first();

        \Illuminate\Support\Facades\Log::info('Polling checkContactStatus:', [
            'user_id' => $user->id,
            'lecturer_id' => $lecturer->id,
            'request_found' => $request ? $request->id : 'none',
            'status' => $request ? $request->status : 'none'
        ]);

        return response()->json([
            'status' => $request ? $request->status : null
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }
}
