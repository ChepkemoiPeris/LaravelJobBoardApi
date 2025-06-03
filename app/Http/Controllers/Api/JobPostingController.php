<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreJobPostingRequest;
use App\Http\Requests\UpdateJobPostingRequest;
use App\Models\JobPosting; 
use App\Http\Resources\JobPostingResource; 
use Illuminate\Http\JsonResponse;
class JobPostingController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index(): JsonResponse
    {
      
        $query = JobPosting::query()->with(['company', 'jobType'])->where('status', 'active');

      

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
    
        if ($request->filled('job_type_id')) {
            $query->where('job_type_id', $request->job_type_id);
        }
    
        if ($request->filled('min_salary')) {
            $query->where('min_salary', '>=', $request->min_salary);
        }
    
        if ($request->filled('max_salary')) {
            $query->where('max_salary', '<=', $request->max_salary);
        }
    
        $jobs = $query->latest()->paginate(10);
    
        return response()->json(JobPostingResource::collection($jobs));
    }
     /**
     * Get company listings
     */
    public function companyJobs(): JsonResponse
    {
        $companyId = auth()->user()->company->id;

        $jobs = JobPosting::with(['jobType'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(10);

            return response()->json(JobPostingResource::collection($jobs));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobPostingRequest $request): JsonResponse
    {
        $job = JobPosting::create($request->validated());

        return response()->json(new JobPostingResource($job), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPosting $jobPosting): JsonResponse
    {
        $jobPosting->load(['company', 'jobType']);
        return response()->json(new JobPostingResource($jobPosting));
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostingRequest $request, JobPosting $jobPosting): JsonResponse
    {
        $jobPosting->update($request->validated());

        return response()->json(new JobPostingResource($jobPosting));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPosting $jobPosting): JsonResponse
    {
        $jobPosting->delete();

        return response()->json(['message' => 'Job posting deleted successfully']);
    }
}
