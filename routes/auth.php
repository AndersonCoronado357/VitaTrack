<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {


    Route::get('login', [AccountController::class, 'login'])
        ->name('login');

    Route::post('login', [AccountController::class, 'loginPost'])
         ->name('account.loginPost');
});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AccountController::class, 'logout'])
         ->name('account.logout');
});
