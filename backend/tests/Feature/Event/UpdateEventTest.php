<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    public function test_can_update_event()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("api/events/$event->id", [
            'title' => 'Updated Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2022-01-01 00:00:00',
            'all_day' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => 'Updated Calendar Event',
                'all_day' => 1,
                'start' => '2021-01-01 00:00:00',
                'end' => '2022-01-01 00:00:00',
                'calendar_id' => $event->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
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

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_404_returned_when_event_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson('/api/events/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Updated Calendar Event',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_title_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => '',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_title_must_be_a_string()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 0,
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_title_must_not_be_greater_than_255_characters()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => Str::random(256),
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_start_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_start_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => 'test',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_start_must_be_before_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2021-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
                'end' => [
                    'The end date must be the same as or after the start date.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_end_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '',
        ]);
        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
                'end' => [
                    'The end date is required.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_end_must_be_a_valid_date()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',

        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => 'test',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 2 more errors)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
                'end' => [
                    'The end date is invalid.',
                    'The end date must be the same as or after the start date.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_end_must_be_after_or_equal_to_start()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2019-01-02 00:00:00',
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The start date must be the same as or before the end date. (and 1 more error)',
            'errors' => [
                'start' => [
                    'The start date must be the same as or before the end date.',
                ],
                'end' => [
                    'The end date must be the same as or after the start date.',
                ],
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_all_day_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
            'all_day' => '',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }

    public function test_all_day_must_be_a_boolean()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);

        $response = $this->patchJson("/api/events/$event->id", [
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
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

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-02 00:00:00',
        ]);
    }
}
