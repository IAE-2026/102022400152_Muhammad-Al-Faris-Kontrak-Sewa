<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IaeMessagePublisherService
{
    public function publishContractApproved(array $payload, string $bearerToken): array
    {
        $message = [
            'exchange' => 'iae.central.exchange',
            'routing_key' => 'rental.contract.approved',
            'event_name' => 'ContractApproved',
            'service' => config('services.iae.service_name'),
            'team_id' => config('services.iae.team_id'),
            'payload' => $payload,
        ];

        $response = Http::withToken($bearerToken)
            ->acceptJson()
            ->post(config('services.iae.base_url') . '/api/v1/messages/publish', $message);

        if (!$response->successful()) {
            throw new \Exception('Publish event gagal: ' . $response->body());
        }

        return $response->json() ?? [
            'status' => 'success',
            'raw' => $response->body(),
        ];
    }
}