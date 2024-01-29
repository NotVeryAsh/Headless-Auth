<?php


use App\Http\Controllers\Calendar\CalendarController;
use App\Http\Controllers\Calendar\CalendarEventController;
use App\Http\Controllers\User\GetCurrentUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', GetCurrentUserController::class);

    Route::prefix('calendars')->group(function() {

        Route::get('', [CalendarController::class, 'index']);
        Route::post('', [CalendarController::class, 'store']);

        Route::prefix('{calendar}')->group(function() {
            Route::get('', [CalendarController::class, 'show'])->withTrashed();
            Route::put('', [CalendarController::class, 'update']);
            Route::delete('', [CalendarController::class, 'destroy']);
            Route::patch('restore', [CalendarController::class, 'restore'])->withTrashed();
        });
    });

    Route::prefix('calendar-events')->group(function() {
        Route::prefix('{calendar_event}')->group(function() {
            Route::get('', [CalendarEventController::class, 'show'])->withTrashed();
        });
    });
});
