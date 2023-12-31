<?php

use App\Http\Controllers\Auth\GetUserTokenController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\LogoutUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {

    Route::post('get-token', GetUserTokenController::class)->name('get-token');

    Route::post('logout', LogoutUserController::class)->name('logout');
});

// Routes that don't require authentication eg. login and register
Route::middleware('guest:sanctum')->group(function () {

    Route::post('login', LoginUserController::class)->name('login');
    Route::post('register', RegisterUserController::class)->name('register');
});
