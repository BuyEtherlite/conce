<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Administration\Controllers\AdministrationController;
use App\Modules\Administration\Controllers\UserController;
use App\Modules\Administration\Controllers\DepartmentController;
use App\Modules\Administration\Controllers\OfficeController;
use App\Modules\Administration\Controllers\CrmController;
use App\Modules\Administration\Controllers\CoreModulesController;

/*
|--------------------------------------------------------------------------
| Administration Module Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('administration')->name('administration.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('administration.users.index');
    });

    // User Management
    Route::resource('users', UserController::class);

    // Department Management
    Route::resource('departments', DepartmentController::class);

    // Office Management
    Route::resource('offices', OfficeController::class);

    // Core Modules Management
    Route::prefix('core-modules')->name('core-modules.')->group(function () {
        Route::get('/', [CoreModulesController::class, 'index'])->name('index');
        Route::post('/modules/{module}/toggle', [CoreModulesController::class, 'toggle'])->name('core-modules.toggle');
        Route::post('/modules/{module}/features/{feature}/toggle', [CoreModulesController::class, 'toggleFeature'])->name('core-modules.features.toggle');
    });

    // CRM Routes
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CrmController::class, 'index'])->name('index');
        Route::resource('customers', CrmController::class);
        Route::get('/service-requests', [CrmController::class, 'serviceRequests'])->name('service-requests.index');
    });
});