<?php

namespace Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    public function test_can_register()
    {
        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(201);

        $response->assertExactJson([
            'message' => 'User successfully registered.',
            'user' => [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@test.com',
            ],
            'token' => $response['token'],
        ]);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    public function test_password_is_required_when_registering()
    {

        $response = $this->postJson('api/auth/register', [
            'password' => '',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password is required.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_must_be_greater_than_8_characters_when_registering()
    {
        $response = $this->postJson('api/auth/register', [
            'password' => 'a',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Password must be at least 8 characters.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_must_not_be_greater_than_255_characters_when_registering()
    {
        $response = $this->postJson('api/auth/register', [

            'password' => Str::random(256),
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => [
                'Password must not be greater than 255 characters.',
                'Passwords do not match.',
            ],
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_password_must_be_confirmed_when_registering()
    {
        $response = $this->postJson('api/auth/register', [

            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword2',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'password' => 'Passwords do not match.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_name_is_required_when_registering()
    {

        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'tes@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name' => 'Name is required.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_name_must_be_string_when_registering()
    {
        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'tes@test.com',
            'name' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name' => 'Name is invalid.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_name_must_not_be_greater_than_255_characters_when_registering()
    {
        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'tes@test.com',
            'name' => Str::random(256),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name' => 'Name must not be greater than 255 characters.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_is_required_when_registering()
    {

        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => '',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is required.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_must_be_valid_when_registering()
    {

        $response = $this->postJson('api/auth/register', [

            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'email',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email is invalid.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_must_be_unique_when_registering()
    {

        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => 'test@test.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email has already been taken.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }

    public function test_email_must_be_not_be_greater_than_255_characters_when_registering()
    {

        $response = $this->postJson('api/auth/register', [
            'password' => 'TestPassword',
            'password_confirmation' => 'TestPassword',
            'email' => Str::random(256).'@example.com',
            'name' => 'Test User',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email' => 'Email must not be greater than 255 characters.',
        ]);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }
}
