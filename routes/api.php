<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\TagihanController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/retribusi/tagihan', [TagihanController::class, 'index']);
    Route::get('/retribusi/tagihan/belum-lunas', [TagihanController::class, 'belumLunas']);
    Route::get('/retribusi/tagihan/lunas', [TagihanController::class, 'lunas']);
    Route::get('/retribusi/tagihan/{id}', [TagihanController::class, 'show']);
});

// Autentikasi
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);