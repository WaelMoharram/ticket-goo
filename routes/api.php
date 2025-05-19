<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [\App\Http\Controllers\V1\Website\AuthController::class, 'getFootballEvents']);
Route::get('/fetch-events', [\App\Http\Controllers\EventController::class, 'fetchEvents']);

require __DIR__.'/website.php';
require __DIR__.'/dashboard.php';
