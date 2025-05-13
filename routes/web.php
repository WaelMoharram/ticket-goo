<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hello Ticket Goo';
//    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
