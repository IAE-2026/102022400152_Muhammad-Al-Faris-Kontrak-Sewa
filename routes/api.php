<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\SsoController;


Route::get('/contracts', [ContractController::class, 'index']);
Route::post('/contracts', [ContractController::class, 'store']);
Route::get('/contracts/{id}', [ContractController::class, 'show']);
Route::put('/contracts/{id}', [ContractController::class, 'update']);
Route::delete('/contracts/{id}', [ContractController::class, 'destroy']);

Route::post('/sso/login', [SsoController::class, 'login']);
Route::post('/contracts/{id}/approve', [ContractController::class, 'approve']);