<?php

use App\Http\Controllers\NutritionController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/nutrition', [NutritionController::class, 'index'])
    ->name('nutrition.index')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.showNutrition');

Route::get('/nutrition/create', [NutritionController::class, 'create'])
    ->name('nutrition.create')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.createNutrition');

Route::get('/nutrition/edit/{id}', [NutritionController::class, 'edit'])
    ->name('nutrition.edit')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.updateNutrition');

Route::get('/nutrition/history', [NutritionController::class, 'history'])
    ->name('nutrition.history')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.historyNutrition');

Route::get('/nutrition/statistics', [NutritionController::class, 'statistics'])
    ->name('nutrition.statistics')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.statsNutrition');

Route::get('/nutrition/goals', [NutritionController::class, 'goals'])
    ->name('nutrition.goals')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.goalsNutrition');

Route::post('/nutrition/store', [NutritionController::class, 'store'])
    ->name('nutrition.store')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.createNutrition');

Route::put('/nutrition/update', [NutritionController::class, 'update'])
    ->name('nutrition.update')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.updateNutrition');

Route::delete('/nutrition/delete/{id}', [NutritionController::class, 'delete'])
    ->name('nutrition.delete')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.deleteNutrition');

Route::post('/nutrition/goals/update', [NutritionController::class, 'updateGoals'])
    ->name('nutrition.goals.update')
    ->middleware(AuthorizedMiddleware::class . ':Nutrición.goalsNutrition');
