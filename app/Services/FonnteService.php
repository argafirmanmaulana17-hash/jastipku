<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendOtp(string $target, string $otp): array
    {
        $token = env('FONNTE_TOKEN');
        $enabled = filter_var(env('FONNTE_ENABLED', false), FILTER_VALIDATE_BOOLEAN);

        if (! $enabled) {
            return [
                'success' => false,
                'message' => 'FONNTE_ENABLED masih false.',
            ];
        }

        if (! $token) {
            return [
                'success' => false,
                'message' => 'FONNTE_TOKEN kosong.',
            ];
        }

        $target = $this->normalizePhone($target);

        $message = "Kode OTP JastipKu kamu adalah: {$otp}\n\nKode berlaku 5 menit. Jangan bagikan kode ini kepada siapa pun.";

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '0',
                ]);

            $body = $response->body();
            $json = $response->json();

            Log::info('Fonnte OTP response', [
                'target' => $target,
                'http_status' => $response->status(),
                'body' => $body,
                'json' => $json,
            ]);

            return [
                'success' => $response->successful() && ($json['status'] ?? false) === true,
                'message' => $json['reason'] ?? $json['message'] ?? $body,
                'target' => $target,
                'http_status' => $response->status(),
                'raw' => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP via Fonnte', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62'.substr($phone, 1);
        }

        return $phone;
    }
}
