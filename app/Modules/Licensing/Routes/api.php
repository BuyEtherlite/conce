<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Licensing\Controllers\LicensingApiController;

/*
|--------------------------------------------------------------------------
| Licensing Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [LicensingApiController::class, 'getStats'])->name('stats');

// Add your licensing API routes here
