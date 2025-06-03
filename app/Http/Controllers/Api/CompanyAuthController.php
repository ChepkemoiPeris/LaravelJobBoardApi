<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RegisterCompanyUserRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class CompanyAuthController extends BaseAuthController
{
    public function register(RegisterCompanyUserRequest $request): JsonResponse
    {
        $this->createUser($request->validated(), 'company_user');

        return response()->json(['message' => 'Company user registered successfully'], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authenticateUser($request->validated());

        if (!$user || $user->role !== 'company_user') {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('company_token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }
}
