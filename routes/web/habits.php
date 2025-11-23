<?php

use App\Http\Controllers\HabitsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/habits', [HabitsController::class, 'index'])
    ->name('habits.index')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.showHabits');

Route::get('/habits/create', [HabitsController::class, 'create'])
    ->name('habits.create')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.createHabits');

Route::get('/habits/edit/{id}', [HabitsController::class, 'edit'])
    ->name('habits.edit')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.updateHabits');

Route::get('/habits/{id}/statistics', [HabitsController::class, 'statistics'])
    ->name('habits.statistics')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.statsHabits');

Route::post('/habits/store', [HabitsController::class, 'store'])
    ->name('habits.store')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.createHabits');

Route::put('/habits/update', [HabitsController::class, 'update'])
    ->name('habits.update')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.updateHabits');

Route::delete('/habits/delete/{id}', [HabitsController::class, 'delete'])
    ->name('habits.delete')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.deleteHabits');

Route::post('/habits/log-completion', [HabitsController::class, 'logCompletion'])
    ->name('habits.logCompletion')
    ->middleware(AuthorizedMiddleware::class . ':Hábitos.logHabits');
