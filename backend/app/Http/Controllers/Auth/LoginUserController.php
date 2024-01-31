<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    /**
     * Authenticate the user with email and password
     */
    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        $email = $request->validated('email');
        $password = $request->validated('password');

        // Attempt to authenticate the user with the provided email and password
        $authIsSuccessful = Auth::attempt(['email' => $email, 'password' => $password]);

        // Auth failed - we have already established that a user with the provided email exists
        if (! $authIsSuccessful) {

            $message = 'Password is incorrect. Try again or click "Forgot Password" to reset your password.';

            return response()->json([
                'errors' => [
                    'password' => [
                        $message,
                    ],
                ],
                'message' => $message,
            ], 422);
        }

        $user = Auth::user();

        $token = $user->createToken(config('sanctum.token_name'));

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
        ], 200);
    }
}
