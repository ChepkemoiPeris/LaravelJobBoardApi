<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;

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
         
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $application = JobApplication::create($data);

        return response()->json(new JobApplicationResource($application->load('job')));

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, JobApplication $jobApplication)
        {
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
    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication)
    {
        $user = $request->user();
    
        if ($user->role !== 'company_user' || $jobApplication->job->company->id !== $user->company->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $jobApplication->update([
            'status' => $request->input('status'),
        ]);
    
        return new JobApplicationResource($jobApplication);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, JobApplication $jobApplication)
    {
        $user = $request->user();

        // Only job seeker who owns the application can delete
        if ($user->role === 'job_seeker' && $jobApplication->user_id === $user->id) {
            $jobApplication->delete();
            return response()->json(['message' => 'Application deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
}
