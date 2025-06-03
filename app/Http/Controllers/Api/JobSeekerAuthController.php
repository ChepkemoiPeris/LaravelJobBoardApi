<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RegisterJobSeekerRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class JobSeekerAuthController extends BaseAuthController
{
    public function register(RegisterJobSeekerRequest $request): JsonResponse
    {
        $this->createUser($request->validated(), 'job_seeker');

        return response()->json(['message' => 'Job seeker registered successfully'], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authenticateUser($request->validated());

        if (!$user || $user->role !== 'job_seeker') {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('job_seeker_token')->plainTextToken;
 
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }
}
