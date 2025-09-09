<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function sendNotification(string $fcmToken, string $title, string $body, array $data = []): array
    {
        $serverKey = config('services.fcm.server_key');

        $response = Http::withToken($serverKey)
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data,
            ]);

        return $response->json();
    }
}
