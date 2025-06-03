<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\JobSeekerAuthController;
use App\Http\Controllers\Api\CompanyAuthController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Api\JobTypeController;
use App\Http\Controllers\Api\CompanyController;

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
    Route::post('store', [CompanyController::class, 'store']);

    Route::middleware(['auth:sanctum', 'role:company_user'])->group(function () {
        Route::get('dashboard', [CompanyDashboardController::class, 'index']);
        Route::put('update/{company}', [CompanyController::class, 'update']);
        Route::delete('delete/{company}', [CompanyController::class, 'destroy']);
    });
});

//unauthenticated shared routes
Route::get('job-types', [JobTypeController::class, 'index']);
Route::get('companies', [CompanyController::class, 'index']);
Route::get('companies/{company}', [CompanyController::class, 'show']); 