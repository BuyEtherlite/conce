<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Utilities\Controllers\UtilitiesApiController;

/*
|--------------------------------------------------------------------------
| Utilities Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [UtilitiesApiController::class, 'getStats'])->name('stats');

// Add your utilities API routes here
