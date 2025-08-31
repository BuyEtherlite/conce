<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Finance\Controllers\PosController;
use App\Modules\Finance\Controllers\MulticurrencyController;

/*
|--------------------------------------------------------------------------
| Enhanced Finance Module Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('finance')->name('finance.')->group(function () {
    
    // Enhanced POS System Routes
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/bill-lookup', [PosController::class, 'billLookup'])->name('bill-lookup');
        Route::post('/process-payment', [PosController::class, 'processPayment'])->name('process-payment');
        Route::get('/customer-suggestions', [PosController::class, 'customerSuggestions'])->name('customer-suggestions');
        Route::post('/generate-qr-code', [PosController::class, 'generateQrCode'])->name('generate-qr-code');
        Route::get('/sales-analytics', [PosController::class, 'salesAnalytics'])->name('sales-analytics');
        
        // CRUD routes
        Route::get('/create', [PosController::class, 'create'])->name('create');
        Route::post('/store', [PosController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PosController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [PosController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PosController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PosController::class, 'destroy'])->name('destroy');
    });

    // Enhanced Multicurrency Routes
    Route::prefix('multicurrency')->name('multicurrency.')->group(function () {
        Route::get('/', [MulticurrencyController::class, 'index'])->name('index');
        Route::get('/create', [MulticurrencyController::class, 'create'])->name('create');
        Route::post('/store', [MulticurrencyController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MulticurrencyController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MulticurrencyController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MulticurrencyController::class, 'destroy'])->name('destroy');
        Route::post('/convert', [MulticurrencyController::class, 'convert'])->name('convert');
        Route::get('/transactions', [MulticurrencyController::class, 'transactions'])->name('transactions');
        Route::get('/reports', [MulticurrencyController::class, 'reports'])->name('reports');
        Route::get('/revaluation', [MulticurrencyController::class, 'revaluation'])->name('revaluation');
    });
});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api/finance')->name('api.finance.')->group(function () {
    Route::post('/pos/bill-lookup', [PosController::class, 'billLookup'])->name('pos.bill-lookup');
    Route::post('/pos/process-payment', [PosController::class, 'processPayment'])->name('pos.process-payment');
    Route::get('/pos/customer-suggestions', [PosController::class, 'customerSuggestions'])->name('pos.customer-suggestions');
    Route::post('/pos/generate-qr-code', [PosController::class, 'generateQrCode'])->name('pos.generate-qr-code');
    Route::get('/pos/sales-analytics', [PosController::class, 'salesAnalytics'])->name('pos.sales-analytics');
    Route::post('/multicurrency/convert', [MulticurrencyController::class, 'convert'])->name('multicurrency.convert');
});