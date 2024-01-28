<?php

namespace Tests\Feature\Calendar;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreCalendarTest extends TestCase
{
    public function test_can_restore_calendar()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar',
        ]);

        $calendar->delete();

        $response = $this->patchJson("/api/calendars/$calendar->id/restore");
        $response->assertStatus(200);
        $response->assertExactJson([
            'calendar' => [
                'id' => $calendar->id,
                'title' => 'Test Calendar',
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'deleted_at' => null,
            ]
        ]);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => null
        ]);
    }

    public function test_404_returned_when_calendar_not_found()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar',
        ]);

        $calendar->delete();

        $response = $this->patchJson("/api/calendars/test/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => Carbon::now()
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $user = User::factory()->create();

        $calendar = $user->calendars()->create([
            'title' => 'Test Calendar',
        ]);

        $calendar->delete();

        $response = $this->patchJson("/api/calendars/$calendar->id/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => Carbon::now()
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

        $calendar->delete();

        $response = $this->patchJson("/api/calendars/$calendar->id/restore");
        $response->assertStatus(404);

        $this->assertDatabaseHas('calendars', [
            'id' => $calendar->id,
            'deleted_at' => Carbon::now()
        ]);
    }
}
