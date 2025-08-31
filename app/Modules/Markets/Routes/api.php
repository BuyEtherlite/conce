<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Markets\Controllers\MarketsApiController;

/*
|--------------------------------------------------------------------------
| Markets Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [MarketsApiController::class, 'getStats'])->name('stats');

// Add your markets API routes here
