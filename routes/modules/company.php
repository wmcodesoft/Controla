<?php

declare(strict_types=1);

use App\Http\Controllers\Company\ClientController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\PorteriaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'password.changed', 'active', 'company', 'tenant.unscoped'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:company.dashboard')
            ->name('dashboard');

        Route::get('/porteria', [PorteriaController::class, 'enter'])
            ->name('porteria.enter');

        Route::redirect('/clients/select', '/company/porteria')
            ->name('clients.select');

        Route::post('/clients/{client}/activate', [ClientController::class, 'activate'])
            ->name('clients.activate');

        Route::resource('clients', ClientController::class);
    });
