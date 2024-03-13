<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetEventTest extends TestCase
{
    public function test_can_get_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $event = Event::factory()->create([
            'calendar_id' => $calendar->id,
            'title' => 'Test title',
            'all_day' => true,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/events/$event->id");

        $response->assertStatus(200);
        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'all_day' => 1,
                'start' => Carbon::now()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $event->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);
    }

    public function test_can_get_trashed_record()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $event = Event::factory()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $event->delete();

        $response = $this->getJson("/api/events/$event->id");

        $response->assertStatus(200);
        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'all_day' => 0,
                'start' => Carbon::now()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $event->calendar_id,
                'deleted_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $event = Event::factory()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/events/$event->id");
        $response->assertStatus(404);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $event = Event::factory()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/events/$event->id");
        $response->assertStatus(403);
    }

    public function test_404_returned_when_event_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/events/test');
        $response->assertStatus(404);
    }
}
