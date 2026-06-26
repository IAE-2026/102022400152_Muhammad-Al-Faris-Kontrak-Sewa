<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\SsoController;


Route::prefix('v1')->middleware('iae.key')->group(function () {
    Route::post('/auth/login', [SsoController::class, 'login']);

    Route::get('/contracts', [ContractController::class, 'index']);
    Route::get('/contracts/{id}', [ContractController::class, 'show']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::put('/contracts/{id}', [ContractController::class, 'update']);
    Route::delete('/contracts/{id}', [ContractController::class, 'destroy']);

    Route::post('/contracts/{id}/approve', [ContractController::class, 'approve']);
});