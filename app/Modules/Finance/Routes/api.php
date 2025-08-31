<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Finance\Controllers\FinanceApiController;

/*
|--------------------------------------------------------------------------
| Finance Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [FinanceApiController::class, 'getStats'])->name('stats');

// Add your finance API routes here
