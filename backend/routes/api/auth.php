<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\RefreshTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('search', [AuthController::class, 'search']);
Route::post('refresh', [AuthController::class, 'refresh'])
    ->middleware([RefreshTokenMiddleware::class]);
Route::middleware('auth:api')->group(function () {


    Route::post('profile', [AuthController::class, 'updateProfile']);
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::post('addImage', [AuthController::class, 'addImage']);
    Route::delete('deleteImage/{id}', [AuthController::class, 'deleteImage']);

    Route::middleware(['can:admin-only'])->group(function () {
        Route::post('promote-admin/{user}', [AuthController::class, 'promoteToAdmin']);
    });
});


