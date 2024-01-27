<?php

namespace Tests\Feature\User;

use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCurrentUserTest extends TestCase
{
    public function test_can_get_current_user()
    {
        $user = User::factory()->create([
            'name' => 'test_name',
            'email' => 'test@test.com'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertExactJson([
            'user' => [
                'id' => $user->id,
                'name' => 'test_name',
                'email' => 'test@test.com',
                'created_at' => Carbon::now(),
                'email_verified_at' => Carbon::now()
            ]
        ]);
    }

    public function test_401_returned_when_not_authenticated()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }
}
