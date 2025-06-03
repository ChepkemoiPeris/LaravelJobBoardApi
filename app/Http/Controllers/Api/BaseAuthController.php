<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Hash;

class BaseAuthController extends Controller
{
    protected function createUser(array $data, string $role): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $role;

        return User::create($data);
    }

    protected function authenticateUser(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    public function user(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

  

}
