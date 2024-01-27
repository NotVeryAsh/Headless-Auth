<?php

namespace Auth;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('api/auth/logout');

        $response->assertStatus(204);

        $this->assertDatabaseEmpty('personal_access_tokens');
    }
}
