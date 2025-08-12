<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::middleware('api')
    ->prefix('dishes')
    ->group(base_path('routes/api/dish.php'));

Route::middleware('api')
    ->prefix('categories')
    ->group(base_path('routes/api/categories.php'));

Route::middleware('api')
    ->prefix('cart')
    ->group(base_path('routes/api/cart.php'));

Route::middleware('api')
    ->prefix('users')
    ->group(base_path('routes/api/users.php'));

Route::middleware('api')
    ->prefix('orders')
    ->group(base_path('routes/api/order.php'));

Route::middleware('api')
    ->prefix('auth')
    ->group(base_path('routes/api/auth.php'));

Route::middleware('api')
    ->group(base_path('routes/api/payment.php'));


