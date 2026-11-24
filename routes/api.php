<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\LaukController;
use App\Http\Controllers\PengambilanLaukApiController;

// Seluruh endpoint API dilindungi dengan middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('santri', SantriController::class);
    Route::apiResource('lauk', LaukController::class);
    Route::apiResource('pengambilan-lauk', PengambilanLaukApiController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

