<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    public function test_can_create_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $event = Event::query()->first();

        $this->assertDatabaseHas('events', [
            'title' => 'Test Calendar Event',
            'all_day' => 1,
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => 'Test Calendar Event',
                'all_day' => true,
                'start' => '2020-01-01 00:00:00',
                'end' => '2020-01-01 00:00:00',
                'calendar_id' => $calendar->id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();
        $calendar = Calendar::factory()->create();

        $response = $this->postJson("/api/calendars/$calendar->id/events");
        $response->assertStatus(404);

        $this->assertDatabaseEmpty('events');
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/calendars/test/events');
        $response->assertStatus(404);

        $this->assertDatabaseEmpty('events');
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create();

        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => '2020-01-01 00:00:00',
            'end' => '2021-01-01 00:00:00',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseEmpty('events');
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'start' => '2020-01-01 00:00:00',
            'end' => '2021-01-01 00:00:00',
        ]);
        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The title is required.',
            'errors' => [
                'title' => [
                    'The title is required.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_title_must_be_a_string()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 1,
            'start' => '2020-01-01 00:00:00',
            'end' => '2021-01-01 00:00:00',
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

        $this->assertDatabaseEmpty('events');
    }

    public function test_title_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => Str::random(256),
            'start' => '2020-01-01 00:00:00',
            'end' => '2021-01-01 00:00:00',
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

        $this->assertDatabaseEmpty('events');
    }

    public function test_start_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'end' => '2021-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date is required.',
            'errors' => [
                'start' => [
                    'The start date is required.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_start_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'start' => 'test',
            'end' => '2021-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date is invalid.',
            'errors' => [
                'start' => [
                    'The start date is invalid.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_start_must_be_before_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'end' => [
                    'The end date must be the same as or after the start date.',
                ],
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_end_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'end' => [
                    'The end date is required.',
                ],
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_end_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'end' => 'test',
            'start' => '2020-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 2 more errors)',
            'errors' => [
                'end' => [
                    'The end date is invalid.',
                    'The end date must be the same as or after the start date.',
                ],
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_end_must_be_after_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'end' => [
                    'The end date must be the same as or after the start date.',
                ],
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }

    public function test_all_day_must_be_a_boolean()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();
        $response = $this->postJson("api/calendars/$calendar->id/events", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2021-01-01 00:00:00',
            'all_day' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The all day field must be accepted.',
            'errors' => [
                'all_day' => [
                    'The all day field must be accepted.',
                ],
            ],
        ]);

        $this->assertDatabaseEmpty('events');
    }
}
