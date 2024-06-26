<?php

namespace Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyEventTest extends TestCase
{
    public function test_can_destroy_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/events/$event->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => 'Test Calendar Event',
                'all_day' => 1,
                'start' => Carbon::today()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $calendar->id,
                'deleted_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        $calendar = Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/events/$event->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/events/$event->id");
        $response->assertStatus(403);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson('/api/events/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);
    }
}
