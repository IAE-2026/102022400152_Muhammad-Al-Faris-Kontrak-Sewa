<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\CentralIntegrationLog;
use App\Services\IaeMessagePublisherService;
use App\Services\IaeSoapAuditService;
use App\Services\IaeSsoService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ContractController extends Controller
{
    #[OA\Get(
        path: "/api/v1/contracts",
        summary: "Get all contracts",
        tags: ["Contracts"],
        security: [['iaeKey' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function index()
    {
        $contracts = Contract::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Contracts retrieved successfully',
            'data' => $contracts
        ]);
    }

    #[OA\Post(
        path: "/api/v1/contracts",
        summary: "Create contract",
        tags: ["Contracts"],
        security: [['iaeKey' => []]],
        responses: [
            new OA\Response(
                response: 201,
                description: "Created"
            )
        ]
    )]
    public function store()
    {
        $contract = Contract::create([
            'contract_number' => 'CTR-' . time(),
            'property_id' => 'PROP-001',
            'tenant_id' => 'TEN-001',
            'status' => 'draft',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'monthly_rent' => 2500000,
            'deposit_amount' => 5000000,
            'terms_and_conditions' => 'Sample contract',
            'created_by' => '102022400152'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contract created successfully',
            'data' => $contract
        ], 201);
    }

    #[OA\Get(
        path: "/api/v1/contracts/{id}",
        summary: "Get contract by ID",
        tags: ["Contracts"],
        security: [['iaeKey' => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function show($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
              'status' => 'error',
              'message' => 'Contract not found',
              'errors' => null,
    ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Contract retrieved successfully',
            'data' => $contract
]);
    }

    public function update($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contract not found'
            ], 404);
        }

        $contract->update([
            'status' => 'active'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contract updated successfully',
            'data' => $contract
        ]);
    }

    public function destroy($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contract not found'
            ], 404);
        }

        $contract->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Contract deleted successfully'
        ]);
    }

    public function approve(
        Request $request,
        $id,
        IaeSsoService $sso,
        IaeSoapAuditService $soapAudit,
        IaeMessagePublisherService $publisher
    ) {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contract not found'
            ], 404);
        }

        $validated = $request->validate([
            'sso_email' => 'required|email',
            'tenant_id' => 'nullable',
            'property_id' => 'nullable',
        ]);

        try {
            $token = $sso->getMachineToken();

            $contract->status = 'signed';
            $contract->sso_email = $validated['sso_email'];
            $contract->approved_at = now();
            $contract->save();

            $payload = [
                'contract_id' => $contract->id,
                'contract_number' => $contract->contract_number,
                'activity' => 'ContractApproved',
                'tenant_id' => $validated['tenant_id'] ?? $contract->tenant_id ?? null,
                'property_id' => $validated['property_id'] ?? $contract->property_id ?? null,
                'approved_by' => $validated['sso_email'],
                'status' => $contract->status,
                'approved_at' => $contract->approved_at?->toDateTimeString(),
                'service' => 'kontrak-sewa-service',
            ];

            $soapResult = $soapAudit->sendAudit('ContractApproved', $payload, $token);

            $publishResult = $publisher->publishContractApproved($payload, $token);

            $contract->soap_receipt_number = $soapResult['receipt_number'];
            $contract->central_publish_status = 'published';
            $contract->save();

            CentralIntegrationLog::create([
                'activity_name' => 'ContractApproved',
                'contract_id' => $contract->id,
                'receipt_number' => $soapResult['receipt_number'],
                'publish_status' => 'published',
                'payload' => $payload,
                'response_body' => $soapResult['response_body'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Kontrak berhasil disetujui, SOAP audit terkirim, dan event RabbitMQ berhasil dipublish.',
                'data' => [
                    'contract' => $contract,
                    'soap_receipt_number' => $soapResult['receipt_number'],
                    'publish_result' => $publishResult,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal integrasi ke sistem pusat IAE.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}