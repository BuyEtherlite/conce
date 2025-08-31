<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Health\Controllers\HealthApiController;

/*
|--------------------------------------------------------------------------
| Health Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [HealthApiController::class, 'getStats'])->name('stats');

// Add your health API routes here
