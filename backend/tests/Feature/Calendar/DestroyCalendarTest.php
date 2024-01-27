<?php

namespace Tests\Feature\Calendar;

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

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");

        $response->assertStatus(204);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => Carbon::now()
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $user = User::factory()->create();

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null
        ]);
    }

    public function test_404_returned_when_user_does_not_have_permission()
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->create();

        Sanctum::actingAs($userTwo);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar'
        ]);

        $response = $this->deleteJson("/api/calendars/$calendar->id");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null
        ]);
    }
}
