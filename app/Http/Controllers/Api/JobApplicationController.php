<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreJobApplicationRequest;
use App\Http\Requests\Api\UpdateJobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
     
        $user = $request->user();

        if ($user->role === 'job_seeker') {
            $applications = JobApplication::with('job')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        } elseif ($user->role === 'company_user') {
            $applications = JobApplication::with('job')
                ->whereHas('job', function ($q) use ($user) {
                    $q->where('company_id', $user->company->id);
                })
                ->latest()
                ->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
 
        return response()->json(JobApplicationResource::collection($applications));
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobApplicationRequest $request): JsonResponse
    {
         
        $user = $request->user();
        $jobPostingId = $request->input('job_posting_id');
    
        // Check if the user has already applied
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_posting_id', $jobPostingId)
            ->first();
    
        if ($existingApplication) {
            return response()->json([
                'message' => 'You have already applied to this job.'
            ], 409); 
        }

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'applied';

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $application = JobApplication::create($data);

        Log::info("User {$user->id} applied to job {$jobPostingId} at " . now());

        return response()->json(new JobApplicationResource($application->load('job')));

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
        {
            $jobApplication = JobApplication::with('job')->find($id);

            if (!$jobApplication) {
                return response()->json([
                    'message' => 'jobApplication not found'
                ], 404);
            }
            $user = $request->user();
           
            // Job seeker can see their own applications
            if ($user->role === 'job_seeker' && $jobApplication->user_id === $user->id) {
                return new JobApplicationResource($jobApplication);
            }
 
            // Company user can see applications for their jobs
            if ($user->role === 'company_user' && $jobApplication->job->company_id === $user->company_id) {
                return new JobApplicationResource($jobApplication);
            }

            return response()->json(['message' => 'Unauthorized'], 403);
        }

  
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobApplicationRequest $request, $id)
    {

        $jobApplication = JobApplication::with('job')->find($id);

        if (!$jobApplication) {
            return response()->json([
                'message' => 'jobApplication not found'
            ], 404);
        }
        $user = $request->user();
    
        if ($user->role !== 'company_user' || $jobApplication->job->company->id !== $user->company->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $jobApplication->update([
            'status' => $request->input('status'),
        ]);
        Log::info("Job {$id} status updated to '{$jobApplication->status}' by user {$user->id} at " . now());

        return new JobApplicationResource($jobApplication);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $jobApplication = JobApplication::with('job')->find($id);
        
        if (!$jobApplication) {
            return response()->json([
                'message' => 'jobApplication not found'
            ], 404);
        }
        $user = $request->user();

        // Only job seeker who owns the application can delete
        if ($user->role === 'job_seeker' && $jobApplication->user_id === $user->id) {
            $jobApplication->delete();
            Log::info("Application {$id} deleted by user {$user->id} at " . now());
            return response()->json(['message' => 'Application deleted successfully']);
        }


        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
}
