<?php

use App\Http\Controllers\SleepController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/sleep', [SleepController::class, 'index'])
    ->name('sleep.index')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.showSleep');

Route::get('/sleep/create', [SleepController::class, 'create'])
    ->name('sleep.create')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.createSleep');

Route::get('/sleep/edit/{id}', [SleepController::class, 'edit'])
    ->name('sleep.edit')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.updateSleep');

Route::get('/sleep/history', [SleepController::class, 'history'])
    ->name('sleep.history')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.historySleep');

Route::get('/sleep/statistics', [SleepController::class, 'statistics'])
    ->name('sleep.statistics')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.statsSleep');

Route::get('/sleep/goals', [SleepController::class, 'goals'])
    ->name('sleep.goals')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.goalsSleep');

Route::post('/sleep/store', [SleepController::class, 'store'])
    ->name('sleep.store')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.createSleep');

Route::put('/sleep/update', [SleepController::class, 'update'])
    ->name('sleep.update')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.updateSleep');

Route::delete('/sleep/delete/{id}', [SleepController::class, 'delete'])
    ->name('sleep.delete')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.deleteSleep');

Route::post('/sleep/goals/update', [SleepController::class, 'updateGoals'])
    ->name('sleep.goals.update')
    ->middleware(AuthorizedMiddleware::class . ':Sueño y Descanso.goalsSleep');
