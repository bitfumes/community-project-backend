<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class);
Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::middleware(['signed'])
        ->post(
            '/email/verify/{id}/{hash}',
            [VerificationController::class, 'verify']
        )
        ->name('verification.verify');

    Route::middleware(['throttle:6,1'])
        ->post(
            '/email/verification-notification',
            [VerificationController::class, 'send']
        );
});


