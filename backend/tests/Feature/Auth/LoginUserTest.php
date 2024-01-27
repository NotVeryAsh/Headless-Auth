<?php

namespace Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    public function test_user_can_login_with_valid_email()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make($password = 'password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'user' => [
                'id' => $user->id,
                'name' => 'Test User',
                'email' => 'test@test.com',
                'created_at' => Carbon::now(),
                'email_verified_at' => Carbon::now()
            ],
            'token' => $response['token'],
        ]);
    }

    public function test_login_fails_when_invalid_email_is_provided()
    {
        $response = $this->postJson('api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is incorrect.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_login_fails_when_invalid_password_is_provided()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'test_password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_field_is_required()
    {
        $response = $this->postJson('api/auth/login', [
            'password' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is required.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_field_is_required()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password is required.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_must_not_be_greater_than_255_characters()
    {
        $response = $this->postJson('api/auth/login', [
            'email' => Str::random(256),
            'password' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is incorrect.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_must_not_be_greater_than_255_characters()
    {
        $response = $this->postJson('api/auth/login', [
            'email' => 'test',
            'password' => Str::random(256),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_must_be_a_string()
    {
        $response = $this->postJson('api/auth/login', [
            'email' => 123,
            'password' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is incorrect.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_must_be_a_string()
    {
        $response = $this->postJson('api/auth/login', [
            'email' => 'test',
            'password' => 123,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password is incorrect. Try again or click "Forgot Password" to reset your password.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }
}
