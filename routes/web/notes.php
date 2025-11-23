<?php

use App\Http\Controllers\NotesController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/notes', [NotesController::class, 'index'])
     ->name('notes.index')
     ->middleware(AuthorizedMiddleware::class . ':Notas.showNotes');

Route::get('/notes/create', [NotesController::class, 'create'])
     ->name('notes.create')
     ->middleware(AuthorizedMiddleware::class . ':Notas.createNotes');

Route::get('/notes/edit/{id}', [NotesController::class, 'edit'])
     ->name('notes.edit')
     ->middleware(AuthorizedMiddleware::class . ':Notas.updateNotes');

Route::get('/notes/{id}/show', [NotesController::class, 'show'])
     ->name('notes.show')
     ->middleware(AuthorizedMiddleware::class . ':Notas.showNotes');

Route::delete('/notes/delete/{id}', [NotesController::class, 'delete'])
     ->name('notes.delete')
     ->middleware(AuthorizedMiddleware::class . ':Notas.deleteNotes');

Route::post('/notes/store', [NotesController::class, 'store'])
     ->name('notes.store')
     ->middleware(AuthorizedMiddleware::class . ':Notas.createNotes');

Route::put('/notes/update', [NotesController::class, 'update'])
     ->name('notes.update')
     ->middleware(AuthorizedMiddleware::class . ':Notas.updateNotes');
