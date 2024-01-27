<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    /**
     * Register and authenticate the user
     */
    public function __invoke(RegisterUserRequest $request): JsonResponse
    {
        // Get validated data
        $username = $request->validated('name');
        $email = $request->validated('email');
        $password = $request->validated('password');

        // Create a user based on validated data
        $user = User::query()->create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = $user->createToken(config('sanctum.token_name'));

        // TODO Send welcome email

        // TODO Uncomment this event to send email verification notification
        // event(new Registered($user));

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token->plainTextToken,
        ], 201);
    }
}
