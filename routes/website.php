<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('website')->name('website.')->group( function () {
    Route::post('/login', [\App\Http\Controllers\V1\Website\AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::middleware('auth:api')->group(function () {
        Route::get('/profile', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [\App\Http\Controllers\V1\Website\AuthController::class, 'logout']);
        Route::post('/refresh-token', [\App\Http\Controllers\V1\Website\AuthController::class, 'refreshToken']);

    });
});
