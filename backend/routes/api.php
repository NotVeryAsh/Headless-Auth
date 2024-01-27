<?php


use App\Http\Controllers\User\GetCurrentUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', GetCurrentUserController::class);

    Route::prefix('calendars')->group(function() {
        Route::prefix('{calendar}')->group(function() {

        });
    });

    Route::prefix('calendar-events')->group(function() {
        Route::prefix('{calendar_event}')->group(function() {

        });
    });
});
