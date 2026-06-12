<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IaeSoapAuditService
{
    public function sendAudit(string $activityName, array $payload, string $bearerToken): array
    {
        $teamId = config('services.iae.team_id');

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central/audit">
    <soap:Body>
        <iae:AuditRequest>
            <iae:TeamID>{$teamId}</iae:TeamID>
            <iae:ActivityName>{$activityName}</iae:ActivityName>
            <iae:LogContent><![CDATA[{$jsonPayload}]]></iae:LogContent>
        </iae:AuditRequest>
    </soap:Body>
</soap:Envelope>
XML;

        $response = Http::withToken($bearerToken)
            ->withHeaders([
                'Content-Type' => 'text/xml; charset=utf-8',
                'Accept' => 'text/xml, application/xml',
            ])
            ->send('POST', config('services.iae.base_url') . '/soap/v1/audit', [
                'body' => $xml,
            ]);

        if (!$response->successful()) {
            throw new \Exception('SOAP audit gagal: ' . $response->body());
        }

        $body = $response->body();

        preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/', $body, $match);

        return [
            'receipt_number' => $match[1] ?? null,
            'response_body' => $body,
        ];
    }
}