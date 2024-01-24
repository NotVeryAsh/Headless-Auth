<?php

use App\Http\Controllers\CalendarEventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('calendar')->group(function() {
    Route::get('events', [CalendarEventController::class, 'index']);
});
