<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class TelegramService
{
    protected $botToken;

    public function __construct()
    {
        // config/services.php uses 'token' key for telegram — use that to avoid null token
        $this->botToken = config('services.telegram.token') ?? config('services.telegram.bot_token');
    }

    public function sendMessage($chatId, $message, $keyboard = null)
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $data = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ];

        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }

        try {
            $response = Http::post($url, $data);
            return $response->json();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}