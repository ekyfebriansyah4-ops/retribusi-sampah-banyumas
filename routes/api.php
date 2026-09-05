<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\Api\GolonganTarifController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\TagihanController as AdminTagihanController;
use App\Http\Controllers\Api\QrisController;

// Autentikasi
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

// User — Tagihan
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/retribusi/tagihan', [TagihanController::class, 'index']);
    Route::get('/retribusi/tagihan/belum-lunas', [TagihanController::class, 'belumLunas']);
    Route::get('/retribusi/tagihan/lunas', [TagihanController::class, 'lunas']);
    Route::get('/retribusi/tagihan/{id}', [TagihanController::class, 'show']);
    Route::post('/qris/create', [QrisController::class, 'create']);
    Route::post('/qris/simulasi-bayar/{reference}', [QrisController::class, 'simulasiBayar']);
    Route::get('/retribusi/info-tagihan/{iduser}', [TagihanController::class, 'infoTagihan']);

    // Golongan Tarif — semua bisa lihat
    Route::get('/golongan-tarif', [GolonganTarifController::class, 'index']);

    // Golongan Tarif — Admin only
    Route::middleware('role:admin')->group(function () {
        Route::post('/golongan-tarif', [GolonganTarifController::class, 'store']);
        Route::put('/golongan-tarif/{id}', [GolonganTarifController::class, 'update']);
        Route::delete('/golongan-tarif/{id}', [GolonganTarifController::class, 'destroy']);
    });
});

// Admin
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::put('/users/{id}/golongan', [AdminUserController::class, 'updateGolongan']);
    Route::put('/users/{id}/nonaktifkan', [AdminUserController::class, 'nonaktifkan']);
    Route::put('/users/{id}/aktifkan', [AdminUserController::class, 'aktifkan']);
    Route::get('/dashboard', [AdminTagihanController::class, 'dashboard']);

    Route::get('/tagihan', [AdminTagihanController::class, 'index']);
    Route::post('/tagihan/generate', [AdminTagihanController::class, 'generate']);
    Route::post('/tagihan/generate-semua', [AdminTagihanController::class, 'generateSemua']);
    Route::put('/tagihan/{id}/verifikasi', [AdminTagihanController::class, 'verifikasiBayar']);
});