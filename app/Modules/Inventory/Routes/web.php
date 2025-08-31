<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Inventory\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Inventory Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [InventoryController::class, 'index'])->name('index');
Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');

// Add your inventory specific routes here
