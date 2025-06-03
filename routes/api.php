<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\JobSeekerAuthController;
use App\Http\Controllers\Api\CompanyAuthController;
use App\Http\Middleware\CheckRole;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

});

Route::prefix('jobseeker')->group(function () {
    Route::post('user/register', [JobSeekerAuthController::class, 'register']);
    Route::post('user/login', [JobSeekerAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:job_seeker'])->group(function () {
        Route::get('/jobseeker/dashboard', [JobSeekerDashboardController::class, 'index']);
    });
});

Route::prefix('company')->group(function () {
    Route::post('user/register', [CompanyAuthController::class, 'register']);
    Route::post('user/login', [CompanyAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:company_user'])->group(function () {
        Route::get('/company/dashboard', [CompanyDashboardController::class, 'index']);
    });
});