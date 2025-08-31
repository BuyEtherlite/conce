<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Property\Controllers\PropertyApiController;

/*
|--------------------------------------------------------------------------
| Property Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [PropertyApiController::class, 'getStats'])->name('stats');

// Add your property API routes here
