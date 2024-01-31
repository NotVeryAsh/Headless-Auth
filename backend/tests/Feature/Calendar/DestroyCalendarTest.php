<?php

namespace Tests\Feature\Calendar;

use App\Models\Calendar;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyCalendarTest extends TestCase
{
    public function test_can_destroy_calendar()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");

        $response->assertStatus(200);

        $response->assertExactJson([
            'calendar' => [
                'id' => $calendar->id,
                'title' => 'Test Calendar',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'deleted_at' => Carbon::now(),
            ],
        ]);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        User::factory()->create();

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null,
        ]);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null,
        ]);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $response = $this->deleteJson('/api/calendars/test');
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null,
        ]);
    }
}
