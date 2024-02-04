<?php

namespace Tests\Feature\CalendarEvent;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreCalendarEventTest extends TestCase
{
    public function test_can_restore_calendar_event()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
            'all_day' => 0,
        ]);

        $calendarEvent->delete();

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id/restore");
        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar_event' => [
                'id' => $calendarEvent->id,
                'title' => 'Test Calendar Event',
                'all_day' => 0,
                'start' => '2020-01-01 00:00:00',
                'end' => '2020-01-01 00:00:00',
                'calendar_id' => $calendarEvent->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => null,
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
            'end' => '2020-01-01 00:00:00',
        ]);

        $calendarEvent->delete();

        $response = $this->patchJson('/api/calendar-events/test/restore');
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $calendarEvent->delete();

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        Calendar::factory()->create();

        $calendarEvent = CalendarEvent::factory()->create([
            'title' => 'Test Calendar Event',
            'start' => '2020-01-01 00:00:00',
            'end' => '2020-01-01 00:00:00',
        ]);

        $calendarEvent->delete();

        $response = $this->patchJson("/api/calendar-events/$calendarEvent->id/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendar_events', [
            'id' => $calendarEvent->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
