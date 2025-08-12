<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    Route::get('/', [DishController::class, 'index']);
    Route::get('/search', [DishController::class, 'search']); 
    Route::post('/', [DishController::class, 'store'])->middleware('auth:api');
    Route::get('/{id}', [DishController::class, 'show']);
    Route::post('/{id}', [DishController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [DishController::class, 'destroy'])->middleware('auth:api');
    Route::post('/rate/{dish}', [DishController::class, 'rateDish']);

});