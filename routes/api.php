<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PreAuthorizationController;
use App\Http\Controllers\Api\CorrespondenceController;
use App\Http\Controllers\Api\VisitorController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::apiResource('pre-authorizations', PreAuthorizationController::class)->except(['update']);
    Route::get('correspondence', [CorrespondenceController::class, 'index']);
    Route::get('correspondence/{correspondence}', [CorrespondenceController::class, 'show']);

    Route::get('visitors/search', [VisitorController::class, 'search']);
});
