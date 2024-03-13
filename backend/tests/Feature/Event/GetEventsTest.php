<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetEventsTest extends TestCase
{
    public function test_can_get_events()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $eventOne = Event::factory()->create([
            'title' => 'Test Calendar Event 1',
            'all_day' => 1,
            'start' => Carbon::yesterday(),
            'end' => Carbon::tomorrow(),
        ]);

        // Test Calendar Event 2 should not be returned since it is trashed
        $eventTwo = Event::factory()->create([
            'title' => 'Test Calendar Event 2',
            'all_day' => 0,
            'start' => Carbon::now(),
            'end' => Carbon::now(),
        ]);

        // Test Calendar Event 3 should not be returned since it is trashed
        $eventThree = Event::factory()->create([
            'title' => 'Test Calendar Event 3',
        ]);

        $eventThree->delete();

        $response = $this->getJson("/api/calendars/$calendar->id/events");

        $response->assertExactJson([
            'events' => [
                [
                    'id' => $eventOne->id,
                    'title' => 'Test Calendar Event 1',
                    'all_day' => 1,
                    'start' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                    'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                    'calendar_id' => $calendar->id,
                    'deleted_at' => null,
                    'created_at' => Carbon::now(),
                ],
                [
                    'id' => $eventTwo->id,
                    'title' => 'Test Calendar Event 2',
                    'all_day' => 0,
                    'start' => Carbon::now()->format('Y-m-d H:i:s'),
                    'end' => Carbon::now()->format('Y-m-d H:i:s'),
                    'calendar_id' => $calendar->id,
                    'deleted_at' => null,
                    'created_at' => Carbon::now(),
                ],
            ],
        ]);
    }

    public function test_can_get_trashed_events()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $eventOne = Event::factory()->create([
            'title' => 'Test Calendar Event 1',
            'all_day' => 1,
            'start' => Carbon::yesterday()->format('Y-m-d H:i:s'),
            'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
        ]);

        $eventOne->delete();

        // Test Calendar Event 2 should not be returned since it is not trashed
        Event::factory()->create([
            'title' => 'Test Calendar Event 2',
            'all_day' => 0,
            'start' => Carbon::now(),
            'end' => Carbon::now(),
        ]);

        $response = $this->getJson("/api/calendars/$calendar->id/events?trashed=1");

        $response->assertExactJson([
            'events' => [
                [
                    'id' => $eventOne->id,
                    'title' => 'Test Calendar Event 1',
                    'all_day' => 1,
                    'start' => Carbon::yesterday()->format('Y-m-d H:i:s'),
                    'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                    'calendar_id' => $calendar->id,
                    'deleted_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ],
            ],
        ]);
    }

    public function test_trashed_must_be_a_boolean()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $response = $this->getJson("/api/calendars/$calendar->id/events?trashed=test");

        $response->assertExactJson([
            'message' => 'The trashed field must be true or false.',
            'errors' => [
                'trashed' => [
                    'The trashed field must be true or false.',
                ],
            ],
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();
        $calendar = Calendar::factory()->create();

        $response = $this->getJson("/api/calendars/$calendar->id/events?trashed=1");
        $response->assertStatus(404);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/calendars/$calendar->id/events");
        $response->assertStatus(403);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/calendars/test/events');
        $response->assertStatus(404);
    }
}
