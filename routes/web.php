<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Show order creation form (GET request)
Route::get('/order/create', [OrderController::class,'create']);

// Handle form submission and store order data (POST request)
Route::post('/order/store', [OrderController::class,'store'])
    ->name('order.store');