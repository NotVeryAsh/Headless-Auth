<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetUserTokenController extends Controller
{
    /**
     * Get a new token for the user to remain logged in.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete all tokens for the user
        $user->tokens()->delete();

        // Create a new token so the user can remain logged in until the new token expires
        $token = $request->user()->createToken(config('sanctum.token_name'));

        return response()->json([
            'message' => 'New token successfully created.',
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
        ], 201);
    }
}
