<?php

use App\Http\Controllers\MedicationsController;
use Illuminate\Support\Facades\Route;

Route::get('/medications', [MedicationsController::class, 'index'])->name('medications.index');
Route::get('/medications/create', [MedicationsController::class, 'create'])->name('medications.create');
Route::get('/medications/edit/{id}', [MedicationsController::class, 'edit'])->name('medications.edit');

Route::post('/medications/store', [MedicationsController::class, 'store'])->name('medications.store');
Route::put('/medications/update', [MedicationsController::class, 'update'])->name('medications.update');
Route::delete('/medications/delete/{id}', [MedicationsController::class, 'delete'])->name('medications.delete');
