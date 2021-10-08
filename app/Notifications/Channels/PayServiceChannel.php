<?php

namespace App\Notifications\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PayServiceChannel
{
    private const BASE_URL = 'https://o4d9z.mocklab.io/notify';

    public function send($notifiable, Notification $notification) : bool
    {
        $id = $notifiable->getKey();
        $data = $notification->toArray($notifiable);

        try {
            // Simulate the data being sent to the mock: $id, $data
            $response = Http::get(self::BASE_URL);

            return ($response->status() === Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
