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

// Store / Update order
Route::post(
    '/order/store',
    [OrderController::class, 'store']
)->name('order.store');

// Order management
Route::get(
    '/orders',
    [OrderController::class, 'index']
)->name('orders.index');

// Order details
Route::get(
    '/orders/{order}',
    [OrderController::class, 'show']
)->name('orders.show');

// Edit order
Route::get(
    '/orders/{order}/edit',
    [OrderController::class, 'edit']
)->name('orders.edit');

// Delete order
Route::delete(
    '/orders/{order}',
    [OrderController::class, 'destroy']
)->name('orders.destroy');

// Duplicate order
Route::post(
    '/orders/{order}/duplicate',
    [OrderController::class, 'duplicate']
)->name('orders.duplicate');

// Bulk delete
Route::post(
    '/orders/bulk-delete',
    [OrderController::class, 'bulkDelete']
)->name('orders.bulk-delete');

// CSV export
Route::get(
    '/orders/export/csv',
    [OrderController::class, 'export']
)->name('orders.export');


/*
|--------------------------------------------------------------------------
| Validation Analytics Routes
|--------------------------------------------------------------------------
*/

// Validation dashboard
Route::get(
    '/validation/dashboard',
    [ValidationDashboardController::class, 'dashboard']
)->name('validation.dashboard');

// Validation history
Route::get(
    '/validation/history',
    [ValidationDashboardController::class, 'history']
)->name('validation.history');