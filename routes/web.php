<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Auth::routes();

// Protected routes - requires authentication
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes (All users)
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');
    
    // ADMIN ONLY Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('items', ItemController::class);
        
        // Restock Routes
        Route::get('/restock', [App\Http\Controllers\RestockController::class, 'index'])->name('restock.index');
        Route::post('/restock', [App\Http\Controllers\RestockController::class, 'store'])->name('restock.store');
        Route::get('/restock/history', [App\Http\Controllers\RestockController::class, 'history'])->name('restock.history');
        Route::get('/restock/batch', [App\Http\Controllers\RestockController::class, 'batch'])->name('restock.batch');
        Route::post('/restock/batch', [App\Http\Controllers\RestockController::class, 'batchStore'])->name('restock.batch.store');
        Route::get('/restock/{id}', [App\Http\Controllers\RestockController::class, 'show'])->name('restock.show');
        
        // Report Routes
        Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.sales');
        Route::get('/reports/sales/export', [App\Http\Controllers\ReportController::class, 'exportSales'])->name('reports.sales.export');
        Route::get('/reports/stock', [App\Http\Controllers\ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/profit', [App\Http\Controllers\ReportController::class, 'profit'])->name('reports.profit');
        
        // User Management Routes
        Route::resource('users', App\Http\Controllers\UserController::class);
    });
    
    // CASHIER ONLY Routes
    Route::middleware(['role:cashier'])->group(function () {
        Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions', [App\Http\Controllers\TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/history', [App\Http\Controllers\TransactionController::class, 'history'])->name('transactions.history');
        Route::get('/transactions/export', [App\Http\Controllers\TransactionController::class, 'export'])->name('transactions.export');
        Route::get('/transactions/{id}', [App\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{id}/receipt', [App\Http\Controllers\TransactionController::class, 'receipt'])->name('transactions.receipt');
    });
    
    // SHARED Routes (Admin & Cashier bisa akses)
    // Lihat produk (read-only untuk cashier, full access untuk admin)
    Route::get('/products', [ItemController::class, 'index'])->name('products.index');
});