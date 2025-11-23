<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/appointments', [AppointmentsController::class, 'index'])
    ->name('appointments.index')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.showAppointments');

Route::get('/appointments/calendar', [AppointmentsController::class, 'calendar'])
    ->name('appointments.calendar')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.calendarAppointments');

Route::get('/appointments/create', [AppointmentsController::class, 'create'])
    ->name('appointments.create')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.createAppointments');

Route::get('/appointments/show/{id}', [AppointmentsController::class, 'show'])
    ->name('appointments.show')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.showAppointments');

Route::get('/appointments/edit/{id}', [AppointmentsController::class, 'edit'])
    ->name('appointments.edit')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.updateAppointments');

Route::post('/appointments/store', [AppointmentsController::class, 'store'])
    ->name('appointments.store')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.createAppointments');

Route::put('/appointments/update', [AppointmentsController::class, 'update'])
    ->name('appointments.update')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.updateAppointments');

Route::delete('/appointments/delete/{id}', [AppointmentsController::class, 'delete'])
    ->name('appointments.delete')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.deleteAppointments');

Route::post('/appointments/update-status', [AppointmentsController::class, 'updateStatus'])
    ->name('appointments.updateStatus')
    ->middleware(AuthorizedMiddleware::class . ':Citas y Calendario.updateAppointments');
