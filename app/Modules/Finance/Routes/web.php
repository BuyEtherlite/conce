<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Finance\Controllers\FinanceController;

/*
|--------------------------------------------------------------------------
| Finance Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [FinanceController::class, 'index'])->name('index');
Route::get('/dashboard', [FinanceController::class, 'dashboard'])->name('dashboard');

// Add your finance specific routes here
