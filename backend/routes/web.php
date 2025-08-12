<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('paypal');
});

Route::post('paywithpaypal', [PaymentController::class, 'create'])->name('paypal.create');
Route::get('paypal/success', [PaymentController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');
