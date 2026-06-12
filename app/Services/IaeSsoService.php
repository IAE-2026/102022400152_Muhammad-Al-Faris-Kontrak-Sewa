<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IaeSsoService
{
    public function getMachineToken(): string
    {
        $response = Http::post(config('services.iae.base_url') . '/api/v1/auth/token', [
            'api_key' => config('services.iae.api_key'),
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gagal mengambil token M2M dari SSO: ' . $response->body());
        }

        $data = $response->json();

        return $data['access_token']
            ?? $data['token']
            ?? $data['jwt']
            ?? throw new \Exception('Token tidak ditemukan pada response SSO.');
    }

    public function loginWarga(string $email, string $password): array
    {
        $response = Http::post(config('services.iae.base_url') . '/api/v1/auth/token', [
            'email' => $email,
            'password' => $password,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Login warga gagal: ' . $response->body());
        }

        $data = $response->json();

        $token = $data['access_token']
            ?? $data['token']
            ?? $data['jwt']
            ?? null;

        if (!$token) {
            throw new \Exception('JWT warga tidak ditemukan.');
        }

        return [
            'token' => $token,
            'payload' => $this->decodeJwtPayload($token),
            'raw' => $data,
        ];
    }

    public function decodeJwtPayload(string $jwt): array
    {
        $parts = explode('.', $jwt);

        if (count($parts) < 2) {
            return [];
        }

        $payload = $parts[1];
        $payload = str_replace(['-', '_'], ['+', '/'], $payload);
        $payload = base64_decode($payload);

        return json_decode($payload, true) ?? [];
    }
}