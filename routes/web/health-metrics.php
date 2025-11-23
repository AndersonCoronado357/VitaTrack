<?php

use App\Http\Controllers\HealthMetricsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/health-metrics', [HealthMetricsController::class, 'index'])
    ->name('health-metrics.index')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.showHealthMetrics');

Route::get('/health-metrics/create', [HealthMetricsController::class, 'create'])
    ->name('health-metrics.create')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.createHealthMetrics');

Route::get('/health-metrics/edit/{id}', [HealthMetricsController::class, 'edit'])
    ->name('health-metrics.edit')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.updateHealthMetrics');

Route::get('/health-metrics/statistics', [HealthMetricsController::class, 'statistics'])
    ->name('health-metrics.statistics')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.statsHealthMetrics');

Route::get('/health-metrics/ranges', [HealthMetricsController::class, 'ranges'])
    ->name('health-metrics.ranges')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.rangesHealthMetrics');

Route::post('/health-metrics/store', [HealthMetricsController::class, 'store'])
    ->name('health-metrics.store')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.createHealthMetrics');

Route::put('/health-metrics/update', [HealthMetricsController::class, 'update'])
    ->name('health-metrics.update')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.updateHealthMetrics');

Route::delete('/health-metrics/delete/{id}', [HealthMetricsController::class, 'delete'])
    ->name('health-metrics.delete')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.deleteHealthMetrics');

Route::post('/health-metrics/ranges/update', [HealthMetricsController::class, 'updateRanges'])
    ->name('health-metrics.ranges.update')
    ->middleware(AuthorizedMiddleware::class . ':Métricas de Salud.rangesHealthMetrics');
