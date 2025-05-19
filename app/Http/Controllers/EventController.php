<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class EventController extends Controller
{
    public function fetchEvents()
    {
        Artisan::call('events:fetch');
        return response()->json(['message' => 'Fetch complete']);
    }
}
