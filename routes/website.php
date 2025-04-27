<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('website')->name('website.')->group( function () {
    Route::post('/register', [\App\Http\Controllers\V1\Website\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\V1\Website\AuthController::class, 'login']);
    Route::post('/social-login/{provider}', [\App\Http\Controllers\V1\Website\AuthController::class, 'socialLogin']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/profile', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [\App\Http\Controllers\V1\Website\AuthController::class, 'logout']);
        Route::post('/refresh-token', [\App\Http\Controllers\V1\Website\AuthController::class, 'refreshToken']);
        Route::get('/me', [\App\Http\Controllers\V1\Website\AuthController::class, 'me']);
    });
});
