<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->middleware('auth:api')->group(function () {
    Route::get('/', [OrderController::class, 'getMyOrders']);
    Route::get('/getOrdersStatistics', [OrderController::class, 'getOrdersStatistics']);
    Route::get('/all', [OrderController::class, 'getAllOrders'])->middleware('can:admin-only');
    Route::post('/', [OrderController::class, 'createOrder'])->middleware('auth:api');
    Route::get('/{id}', [OrderController::class, 'findOrder']);
    Route::put('/{id}', [OrderController::class, 'updateOrder'])->middleware('auth:api');
    Route::post('/download/{id}', [OrderController::class, 'downloadOrderPdf'])->middleware('auth:api');
    Route::delete('/{id}', [OrderController::class, 'deleteOrder'])->middleware('auth:api');
    Route::put('/status/{id}', [OrderController::class, 'updateOrderStatus']);
});
