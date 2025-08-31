<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Markets\Controllers\MarketsController;

/*
|--------------------------------------------------------------------------
| Markets Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [MarketsController::class, 'index'])->name('index');
Route::get('/dashboard', [MarketsController::class, 'dashboard'])->name('dashboard');

// Add your markets specific routes here
