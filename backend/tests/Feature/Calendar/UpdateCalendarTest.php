<?php

namespace Tests\Feature\Calendar;

use App\Models\Calendar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateCalendarTest extends TestCase
{
    public function test_can_update_calendar()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("api/calendars/$calendar->id", [
            'title' => 'Updated Calendar',
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar' => [
                'id' => $calendar->id,
                'title' => 'Updated Calendar',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ]);

        $this->assertDatabaseHas('calendars', [
            'title' => 'Updated Calendar',
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $user = User::factory()->create();

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("/api/calendars/$calendar->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson('/api/calendars/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("/api/calendars/$calendar->id", [
            'title' => 'Updated Calendar',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("/api/calendars/$calendar->id");
        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The title is required.',
            'errors' => [
                'title' => [
                    'The title is required.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }

    public function test_title_must_be_a_string()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("/api/calendars/$calendar->id", [
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

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }

    public function test_title_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->putJson("/api/calendars/$calendar->id", [
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

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'title' => 'Test Calendar',
        ]);
    }
}
