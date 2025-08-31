<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Survey\Controllers\SurveyController;

Route::middleware(['web', 'auth'])->prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    
    // Projects
    Route::get('/projects', [SurveyController::class, 'projects'])->name('projects.index');
    Route::get('/projects/create', [SurveyController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [SurveyController::class, 'storeProject'])->name('projects.store');
    Route::get('/projects/{project}', [SurveyController::class, 'showProject'])->name('projects.show');
    Route::get('/projects/{project}/edit', [SurveyController::class, 'editProject'])->name('projects.edit');
    Route::put('/projects/{project}', [SurveyController::class, 'updateProject'])->name('projects.update');
    
    // Cadastral surveys
    Route::get('/cadastral', [SurveyController::class, 'cadastralSurveys'])->name('cadastral.index');
    
    // Equipment
    Route::get('/equipment', [SurveyController::class, 'equipment'])->name('equipment.index');
    
    // Reports
    Route::get('/reports', [SurveyController::class, 'reports'])->name('reports.index');
});
