<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('paywithpaypal', [PaymentController::class, 'initiate'])->name('paypal.initiate');
    Route::get('paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
    Route::get('paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');
});
