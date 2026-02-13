<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FirebaseService
{
    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->credentialsPath = config('services.firebase.credentials');
    }

    /**
     * Send push notification via FCM v1 API.
     */
    public function sendNotification(string $fcmToken, string $title, string $body, array $data = []): bool
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                Log::error('FirebaseService: Failed to obtain access token');
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $message = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => array_map('strval', $data),
                ],
            ];

            $response = Http::withToken($accessToken)
                ->timeout(10)
                ->post($url, $message);

            if ($response->successful()) {
                Log::info('FirebaseService: Notification sent successfully', [
                    'token' => substr($fcmToken, 0, 20) . '...',
                ]);
                return true;
            }

            Log::error('FirebaseService: FCM API error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('FirebaseService: Exception while sending notification', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Get OAuth 2.0 access token from Google Service Account.
     * Cached for 55 minutes (tokens expire after 60 min).
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember('firebase_access_token', 55 * 60, function () {
            try {
                $credentials = json_decode(file_get_contents($this->credentialsPath), true);

                if (!$credentials) {
                    Log::error('FirebaseService: Invalid service account credentials file');
                    return null;
                }

                $now = time();
                $header = $this->base64UrlEncode(json_encode([
                    'alg' => 'RS256',
                    'typ' => 'JWT',
                ]));

                $payload = $this->base64UrlEncode(json_encode([
                    'iss' => $credentials['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $now + 3600,
                ]));

                $unsignedJwt = "{$header}.{$payload}";

                openssl_sign(
                    $unsignedJwt,
                    $signature,
                    $credentials['private_key'],
                    OPENSSL_ALGO_SHA256
                );

                $jwt = "{$unsignedJwt}." . $this->base64UrlEncode($signature);

                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('FirebaseService: Failed to get access token', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('FirebaseService: Exception getting access token', [
                    'message' => $e->getMessage(),
                ]);
                return null;
            }
        });
    }

    /**
     * Base64 URL-safe encoding (no padding).
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
