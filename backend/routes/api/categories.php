<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', [CategoryController::class, 'getCategories']);
Route::post('/', [CategoryController::class, 'createCategory']);
Route::get('/{category}', [CategoryController::class, 'getCategoryById']);
Route::post('/{id}', [CategoryController::class, 'updateCategory']);
Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);