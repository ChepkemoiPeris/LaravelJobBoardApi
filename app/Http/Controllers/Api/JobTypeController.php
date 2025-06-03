<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\JobType;
use Illuminate\Http\JsonResponse; 

class JobTypeController extends Controller
{
    /**
     * Return all job types
     */
    public function index(): JsonResponse
    {
        $jobTypes = JobType::all(['id', 'name']);
        return response()->json($jobTypes);
    }
    
}
