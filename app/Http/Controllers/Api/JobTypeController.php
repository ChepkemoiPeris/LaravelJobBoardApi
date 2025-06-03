<?php

namespace App\Http\Controllers\Api;
 
use App\Models\JobType;

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
