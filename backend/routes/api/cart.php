<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:api")->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::get('/all', [CartController::class, 'getAllCarts']);
    Route::get('/{cart}', [CartController::class, 'getCartById']);
    Route::post('/', [CartController::class, 'addItem']);
    Route::put('/{dish}', [CartController::class, 'updateItem']);
    Route::delete('/', [CartController::class, 'clear']);
    Route::delete('/{id}', [CartController::class, 'removeItem']);
});
