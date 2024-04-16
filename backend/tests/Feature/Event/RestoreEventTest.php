<?php

namespace Tests\Feature\Event;

use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreEventTest extends TestCase
{
    public function test_can_restore_event()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
            'all_day' => 0,
        ]);

        $event->delete();

        $response = $this->patchJson("/api/events/$event->id/restore");
        $response->assertStatus(200);
        $response->assertExactJson([
            'event' => [
                'id' => $event->id,
                'title' => 'Test Calendar Event',
                'all_day' => 0,
                'start' => '2020-01-01 00:00:00',
                'end' => '2020-01-01 00:00:00',
                'calendar_id' => $event->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null,
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
            'end' => '2020-01-01 00:00:00',
        ]);

        $event->delete();

        $response = $this->patchJson('/api/events/test/restore');
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $event->delete();

        $response = $this->patchJson("/api/events/$event->id/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_403_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        Calendar::factory()->create();

        $event = Event::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $event->delete();

        $response = $this->patchJson("/api/events/$event->id/restore");
        $response->assertStatus(403);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
