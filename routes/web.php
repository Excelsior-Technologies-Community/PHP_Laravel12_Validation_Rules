<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ValidationDashboardController;

/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
*/

// Create order form
Route::get(
    '/order/create',
    [OrderController::class, 'create']
)->name('order.create');

// Store order
Route::post(
    '/order/store',
    [OrderController::class, 'store']
)->name('order.store');

// Order search, filtering and pagination
Route::get(
    '/orders',
    [OrderController::class, 'index']
)->name('orders.index');


/*
|--------------------------------------------------------------------------
| Validation Analytics Routes
|--------------------------------------------------------------------------
*/

// Validation analytics dashboard
Route::get(
    '/validation/dashboard',
    [ValidationDashboardController::class, 'dashboard']
)->name('validation.dashboard');

// Validation failure history
Route::get(
    '/validation/history',
    [ValidationDashboardController::class, 'history']
)->name('validation.history');