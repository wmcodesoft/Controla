<?php

declare(strict_types=1);

use App\Http\Controllers\Platform\CompanyController;
use App\Http\Controllers\Platform\DashboardController;
use App\Http\Controllers\Platform\PricingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'password.changed', 'active', 'platform.admin', 'tenant.unscoped'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:platform.dashboard')
            ->name('dashboard');

        Route::post('/companies/{company}/archive', [DashboardController::class, 'archiveCompany'])
            ->middleware('permission:platform.companies.manage')
            ->name('companies.archive');

        Route::post('/companies/{company}/clients/{client}/release', [DashboardController::class, 'releaseClient'])
            ->middleware('permission:platform.companies.manage')
            ->name('companies.clients.release');

        Route::get('/pricing', [PricingController::class, 'edit'])
            ->middleware('permission:platform.companies.view')
            ->name('pricing.edit');

        Route::put('/pricing', [PricingController::class, 'update'])
            ->middleware('permission:platform.companies.manage')
            ->name('pricing.update');

        Route::get('/companies', [CompanyController::class, 'index'])
            ->middleware('permission:platform.companies.view')
            ->name('companies.index');

        Route::get('/companies/{company}', [CompanyController::class, 'show'])
            ->middleware('permission:platform.companies.view')
            ->name('companies.show');

        Route::put('/companies/{company}/package', [CompanyController::class, 'updatePackage'])
            ->middleware('permission:platform.companies.manage')
            ->name('companies.package.update');
    });
