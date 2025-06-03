<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreJobPostingRequest;
use App\Http\Requests\Api\UpdateJobPostingRequest;
use App\Models\JobPosting; 
use App\Http\Resources\JobPostingResource; 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobPostingController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index(Request $request): JsonResponse
    {
      
        $query = JobPosting::query()->with(['company', 'jobType'])->where('status', 'active');

      

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
    
        if ($request->has('job_type_id')) {
            $query->where('job_type_id', $request->job_type_id);
        }
    
        if ($request->has('min_salary')) {
            $query->where('min_salary', '>=', $request->min_salary);
        }
    
        if ($request->has('max_salary')) {
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
        $companyId = auth()->user()->company_id;

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
        $user = auth()->user();
 
        if (!$user || !$user->company_id) {
            return response()->json(['message' => 'Unauthorized: You must be a company user.'], 403);
        }
    
        $data = array_merge($request->validated(), ['company_id' => $user->company_id]);

        // Check for duplicate job posting
        $exists = JobPosting::where('company_id', $user->company_id)
            ->where('title', $data['title'])
            ->where('location', $data['location'])
            ->whereDate('deadline', $data['deadline'])
            ->exists();
    
        if ($exists) {
            return response()->json(['message' => 'Duplicate job posting already exists.'], 409);
        }
    
    
        $job = JobPosting::create($data);
        Log::info("New job of id {$job->id} and name  {$job->title} posted at " . now());
        return response()->json(new JobPostingResource($job), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $jobPosting = JobPosting::find($id);

        if (!$jobPosting) {
            return response()->json(['message' => 'Job posting not found.'], 404);
        }
    
        $jobPosting->load(['company', 'jobType']);
    
        return response()->json(new JobPostingResource($jobPosting));
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobPostingRequest $request, $id): JsonResponse
    {
        $jobPosting = JobPosting::find($id);
    
        if (!$jobPosting) {
            return response()->json(['message' => 'Job posting not found'], 404);
        }
    
        $user = auth()->user();
    
        if (!$user || $user->company_id !== $jobPosting->company_id) {
            return response()->json([
                'message' => 'Unauthorized: You cannot update this job posting.'
            ], 403);
        }
    
        $jobPosting->update($request->validated());
    
        return response()->json(new JobPostingResource($jobPosting));
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
     
        $jobPosting = JobPosting::find($id);

        if (!$jobPosting) {
            return response()->json(['message' => 'Job posting not found.'], 404);
        }
    
        $user = $request->user();
    
        if (!$user || !$user->company_id || $user->company_id !== $jobPosting->company_id) {
            return response()->json(['message' => 'Unauthorized: You must be the owner of this job posting.'], 403);
        }
    
        $jobPosting->delete();
    
        return response()->json(['message' => 'Job posting deleted successfully']);
    }
}
