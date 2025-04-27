<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->name('dashboard.')->group( function () {
    Route::post('/login', [\App\Http\Controllers\V1\Dashboard\AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    // المسارات اللي محتاجة توكن الأدمن
    Route::middleware('auth:admin')->group(function () {
        Route::get('/profile', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [\App\Http\Controllers\V1\Dashboard\AuthController::class, 'logout']);
        Route::post('/refresh-token', [\App\Http\Controllers\V1\Dashboard\AuthController::class, 'refreshToken']);

    });
});
