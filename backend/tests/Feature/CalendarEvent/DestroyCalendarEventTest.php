<?php

namespace CalendarEvent;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyCalendarEventTest extends TestCase
{
    public function test_can_destroy_calendar_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/calendar-events/$calendarEvent->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'calendar_events' => [
                'id' => $calendarEvent->id,
                'title' => 'Test Calendar Event',
                'all_day' => 1,
                'start' => Carbon::today()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $calendar->id,
                'deleted_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        $calendar = Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/calendar-events/$calendarEvent->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => null,
        ]);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson("/api/calendar-events/$calendarEvent->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => null,
        ]);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'all_day' => true,
            'start' => Carbon::today(),
            'end' => Carbon::tomorrow(),
            'calendar_id' => $calendar->id,
        ]);

        $response = $this->deleteJson('/api/calendar-events/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => null,
        ]);
    }
}
