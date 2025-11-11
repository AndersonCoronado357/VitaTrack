<?php

use App\Http\Controllers\MedicationsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/medications', [MedicationsController::class, 'index'])
    ->name('medications.index')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.showMedications');

Route::get('/medications/create', [MedicationsController::class, 'create'])
    ->name('medications.create')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.createMedications');

Route::get('/medications/edit/{id}', [MedicationsController::class, 'edit'])
    ->name('medications.edit')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.updateMedications');

Route::post('/medications/store', [MedicationsController::class, 'store'])
    ->name('medications.store')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.createMedications');

Route::put('/medications/update', [MedicationsController::class, 'update'])
    ->name('medications.update')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.updateMedications');

Route::delete('/medications/delete/{id}', [MedicationsController::class, 'delete'])
    ->name('medications.delete')
    ->middleware(AuthorizedMiddleware::class . ':Medicamentos.deleteMedications');
