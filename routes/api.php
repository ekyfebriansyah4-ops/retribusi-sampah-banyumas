<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\TagihanController as AdminTagihanController;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::put('/users/{id}/golongan', [AdminUserController::class, 'updateGolongan']);
    Route::put('/users/{id}/nonaktifkan', [AdminUserController::class, 'nonaktifkan']);
    Route::put('/users/{id}/aktifkan', [AdminUserController::class, 'aktifkan']);

    Route::get('/tagihan', [AdminTagihanController::class, 'index']);
    Route::post('/tagihan/generate', [AdminTagihanController::class, 'generate']);
    Route::post('/tagihan/generate-semua', [AdminTagihanController::class, 'generateSemua']);
    Route::put('/tagihan/{id}/verifikasi', [AdminTagihanController::class, 'verifikasiBayar']);
});

// Autentikasi
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);