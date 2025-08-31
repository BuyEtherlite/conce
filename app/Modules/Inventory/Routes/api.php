<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Inventory\Controllers\InventoryApiController;

/*
|--------------------------------------------------------------------------
| Inventory Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [InventoryApiController::class, 'getStats'])->name('stats');

// Add your inventory API routes here
