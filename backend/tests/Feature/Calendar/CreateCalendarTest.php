<?php

namespace Tests\Feature\Calendar;

use App\Models\Calendar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCalendarTest extends TestCase
{
    public function test_can_create_calendar()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('api/calendars', [
            'title' => 'Test Calendar',
        ]);

        $this->assertDatabaseHas('calendars', [
            'title' => 'Test Calendar',
        ]);

        $calendar = Calendar::query()->first();

        $response->assertStatus(201);
        $response->assertExactJson([
            'calendar' => [
                'id' => $calendar->id,
                'title' => 'Test Calendar',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $response = $this->postJson('/api/calendars');
        $response->assertStatus(404);

        $this->assertDatabaseEmpty('calendars');
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/calendars');
        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The title is required.',
            'errors' => [
                'title' => [
                    'The title is required.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('calendars');
    }

    public function test_title_must_be_a_string()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/calendars', [
            'title' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The title is invalid.',
            'errors' => [
                'title' => [
                    'The title is invalid.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('calendars');
    }

    public function test_title_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/calendars', [
            'title' => Str::random(256),
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The title must not be more than 255 characters long.',
            'errors' => [
                'title' => [
                    'The title must not be more than 255 characters long.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('calendars');
    }
}
