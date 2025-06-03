<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\JobSeekerAuthController;
use App\Http\Controllers\Api\BaseAuthController;
use App\Http\Controllers\Api\CompanyAuthController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Api\JobTypeController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobPostingController;
use App\Http\Controllers\Api\JobApplicationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [BaseAuthController::class, 'user']);
    Route::post('logout', [BaseAuthController::class, 'logout']);
    Route::get('applications/{id}', [JobApplicationController::class, 'show']); 
});

Route::prefix('jobseeker')->group(function () {
    Route::post('user/register', [JobSeekerAuthController::class, 'register']);
    Route::post('user/login', [JobSeekerAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:job_seeker'])->group(function () { 
        Route::get('dashboard/applications', [JobApplicationController::class, 'index']);  
        Route::post('jobs/apply/{job}', [JobApplicationController::class, 'store']);  
        Route::delete('applications/{application}', [JobApplicationController::class, 'destroy']);  

    });
});

Route::prefix('company')->group(function () {
    Route::post('user/register', [CompanyAuthController::class, 'register']);
    Route::post('user/login', [CompanyAuthController::class, 'login']);
    Route::post('store', [CompanyController::class, 'store']);

    Route::middleware(['auth:sanctum', 'role:company_user'])->group(function () {
        Route::get('dashboard', [CompanyDashboardController::class, 'index']);

        //company routes
        Route::put('update/{company}', [CompanyController::class, 'update']);
        Route::delete('delete/{company}', [CompanyController::class, 'destroy']);

        //company job listing routes
        Route::get('jobs', [JobPostingController::class, 'companyJobs']);
        Route::post('jobs/store', [JobPostingController::class, 'store']);
        Route::put('jobs/{jobPosting}', [JobPostingController::class, 'update']);
        Route::delete('jobs/{jobPosting}', [JobPostingController::class, 'destroy']);

        //company job applications
        Route::get('applications', [JobApplicationController::class, 'index']);       
        Route::put('applications/{application}', [JobApplicationController::class, 'update']);  

    });
});

//unauthenticated shared routes
Route::get('job-types', [JobTypeController::class, 'index']);
Route::get('companies', [CompanyController::class, 'index']);
Route::get('companies/{company}', [CompanyController::class, 'show']); 
Route::get('jobs', [JobPostingController::class, 'index']);
Route::get('jobs/{job}', [JobPostingController::class, 'show']);