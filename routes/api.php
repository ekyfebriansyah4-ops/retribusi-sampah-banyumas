<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\GolonganTarifController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/golongan-tarif', [GolonganTarifController::class, 'index']); // semua bisa lihat

    Route::middleware('role:admin')->group(function () {
        Route::post('/golongan-tarif', [GolonganTarifController::class, 'store']);
        Route::put('/golongan-tarif/{id}', [GolonganTarifController::class, 'update']);
        Route::delete('/golongan-tarif/{id}', [GolonganTarifController::class, 'destroy']);
    });
});

// Autentikasi
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);