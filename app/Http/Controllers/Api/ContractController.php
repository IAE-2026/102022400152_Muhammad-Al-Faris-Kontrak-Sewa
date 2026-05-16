<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;

use OpenApi\Attributes as OA;

class ContractController extends Controller
{
    #[OA\Get(
    path: "/api/contracts",
    summary: "Get all contracts",
    tags: ["Contracts"],
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
        ]);
    }

    public function show($id)
    {
        $contract = Contract::find($id);

        return response()->json([
            'status' => 'success',
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
}