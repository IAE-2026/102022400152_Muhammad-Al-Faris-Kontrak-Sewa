<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LocalRole;
use App\Services\IaeSsoService;
use Illuminate\Http\Request;

class SsoController extends Controller
{
    public function login(Request $request, IaeSsoService $sso)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $sso->loginWarga($validated['email'], $validated['password']);

        $payload = $result['payload'];

        $localRole = LocalRole::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $payload['name'] ?? $payload['sub'] ?? 'Warga IAE',
                'role' => $payload['role'] ?? 'tenant',
                'sso_payload' => $payload,
            ]
        );

        return response()->json([
            'message' => 'Login SSO berhasil dan user berhasil dipetakan ke role lokal.',
            'token' => $result['token'],
            'payload' => $payload,
            'local_role' => $localRole,
        ]);
    }
}