<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Finance\Controllers\PosController;
use App\Modules\Finance\Controllers\MulticurrencyController;
use App\Modules\Finance\Controllers\IpsasComplianceController;

/*
|--------------------------------------------------------------------------
| Enhanced Finance API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('api/v1/finance')->name('api.finance.')->group(function () {
    
    // Smart POS System API
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::post('bill-lookup', [PosController::class, 'billLookup'])->name('bill-lookup');
        Route::get('customer-suggestions', [PosController::class, 'customerSuggestions'])->name('customer-suggestions');
        Route::post('generate-qr-code', [PosController::class, 'generateQrCode'])->name('generate-qr-code');
        Route::post('process-payment', [PosController::class, 'processPayment'])->name('process-payment');
        Route::get('sales-analytics', [PosController::class, 'salesAnalytics'])->name('sales-analytics');
    });

    // Multicurrency API
    Route::prefix('multicurrency')->name('multicurrency.')->group(function () {
        Route::post('convert', [MulticurrencyController::class, 'convert'])->name('convert');
        Route::post('update-rates', [MulticurrencyController::class, 'updateExchangeRates'])->name('update-rates');
        Route::get('exchange-rate-history/{currencyId}', [MulticurrencyController::class, 'exchangeRateHistory'])->name('exchange-rate-history');
    });

    // IPSAS Compliance API
    Route::prefix('ipsas')->name('ipsas.')->group(function () {
        Route::get('metrics', [IpsasComplianceController::class, 'performanceMetrics'])->name('metrics');
        Route::post('validate', [IpsasComplianceController::class, 'validateCompliance'])->name('validate');
    });
});

// Public API endpoints
Route::middleware(['throttle:60,1'])->prefix('api/v1/public/finance')->name('api.public.finance.')->group(function () {
    Route::get('exchange-rates', [MulticurrencyController::class, 'getPublicExchangeRates'])->name('exchange-rates');
    Route::post('verify-bill', [PosController::class, 'verifyBill'])->name('verify-bill');
});