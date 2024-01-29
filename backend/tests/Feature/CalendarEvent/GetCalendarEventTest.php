<?php

namespace Tests\Feature\CalendarEvent;

use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCalendarEventTest extends TestCase
{
    public function test_can_get_calendar_event()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $calendarEvent = $calendar->calendarEvents()->create([
            'calendar_id' => $calendar->id,
            'title' => 'Test title',
            'all_day' => true,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/calendar-events/$calendarEvent->id");

        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar_event' => [
                'id' => $calendarEvent->id,
                'title' => $calendarEvent->title,
                'all_day' => 1,
                'start' => Carbon::now()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $calendarEvent->calendar_id,
                'deleted_at' => null,
                'created_at' => Carbon::now()
            ]
        ]);
    }

    public function test_can_get_trashed_record()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $calendarEvent = $calendar->calendarEvents()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $calendarEvent->delete();

        $response = $this->getJson("/api/calendar-events/$calendarEvent->id");

        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar_event' => [
                'id' => $calendarEvent->id,
                'title' => $calendarEvent->title,
                'all_day' => 0,
                'start' => Carbon::now()->format('Y-m-d H:i:s'),
                'end' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'calendar_id' => $calendarEvent->calendar_id,
                'deleted_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $user = User::factory()->create();

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $calendarEvent = $calendar->calendarEvents()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/calendar-events/$calendarEvent->id");
        $response->assertStatus(404);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $calendarEvent = $calendar->calendarEvents()->create([
            'title' => 'Test title',
            'all_day' => false,
            'start' => Carbon::now(),
            'end' => Carbon::tomorrow(),
        ]);

        $response = $this->getJson("/api/calendar-events/$calendarEvent->id");
        $response->assertStatus(404);
    }

    public function test_404_returned_when_calendar_event_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/calendar-events/test");
        $response->assertStatus(404);
    }
}
