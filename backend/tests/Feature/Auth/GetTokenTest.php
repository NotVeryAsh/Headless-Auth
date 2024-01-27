<?php

namespace Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetTokenTest extends TestCase
{
    public function test_can_get_token()
    {
        $user = User::factory()->create([
            'name' => 'Test user',
            'email' => 'test@test.com'
        ]);

        // Create a token for the user
        Sanctum::actingAs($user);

        $token = $user->currentAccessToken();

        // request new token
        $response = $this->postJson('api/auth/get-token');

        $response->assertStatus(201);

        // Assert new token has been given with user
        $response->assertExactJson([
            'user' => [
                'id' => $user->id,
                'name' => 'Test user',
                'email' => 'test@test.com',
                'created_at' => Carbon::now(),
                'email_verified_at' => Carbon::now()
            ],
            'token' => $response['token']
        ]);

        // Assert old token has been deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'test_token',
            'token' => $token
        ]);
    }

    public function test_401_returned_when_user_is_not_authenticated()
    {
        $response = $this->postJson('api/auth/get-token');

        $response->assertStatus(401);
    }
}
