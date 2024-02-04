<?php

namespace Tests\Feature\CalendarEvent;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\CalendarEventFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateCalendarEventTest extends TestCase
{
    public function test_can_update_calendar()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("api/calendar-events/$calendarEvent->id", [
            'title' => 'Updated Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2022-01-01 00:00:00',
            'all_day' => 1
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar_event' => [
                'id' => $calendarEvent->id,
                'title' => 'Updated Calendar Event',
                'all_day' => 1,
                'start' => '2021-01-01 00:00:00',
                'end' => '2022-01-01 00:00:00',
                'calendar_id' => $calendarEvent->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Updated Calendar Event',
            'all_day' => 1,
            'start' => '2021-01-01 00:00:00',
            'end' => '2022-01-01 00:00:00',
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_404_returned_when_calendar_event_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson('/api/calendar-events/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Updated Calendar Event',
        ]);

        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => '',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
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

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_title_must_be_a_string()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 0,
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
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

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_title_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => Str::random(256),
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
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

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_start_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
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

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_start_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => 'test',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
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

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_start_must_be_before_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
                'end' => [
                    'The end date must be the same as or after the start date.'
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_end_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '',
            'all_day' => 0
        ]);
        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.'
                ],
                'end' => [
                    'The end date is required.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_end_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => 'test',
            'all_day' => 0
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 2 more errors)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.'
                ],
                'end' => [
                    'The end date is invalid.',
                    'The end date must be the same as or after the start date.'
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_end_must_be_after_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2019-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.'
                ],
                'end' => [
                    'The end date must be the same as or after the start date.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_all_day_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => ''
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The all day field is required.',
            'errors' => [
                'all_day' => [
                    'The all day field is required.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }

    public function test_all_day_must_be_a_boolean()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 'test'
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The all day field must be true or false.',
            'errors' => [
                'all_day' => [
                    'The all day field must be true or false.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => 0
        ]);
    }
}
