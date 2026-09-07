<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class SendLoginNotification implements ShouldQueue
{
    use Queueable;

    protected string $chatId;
    protected string $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $chatId, string $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramService $telegramService): void
    {
        try {
            $telegramService->sendMessage($this->chatId, $this->message);
        } catch (\Exception $e) {
            Log::error('SendLoginNotification job failed: ' . $e->getMessage());
        }
    }
}
