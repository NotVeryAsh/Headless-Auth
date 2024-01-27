<?php

namespace Tests\Feature\Calendar;

use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCalendarsTest extends TestCase
{
    public function test_can_get_calendars()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendarOne = $user->calendars()->create([
            'title' => 'Test Calendar',
        ]);

        $calendarTwo = $user->calendars()->create([
            'title' => 'Test Calendar 2',
        ]);

        $response = $this->getJson('/api/calendars');

        $response->assertExactJson([
            'calendars' => [
                [
                    'id' => $calendarOne->id,
                    'title' => 'Test Calendar',
                    'user_id' => $user->id,
                    'created_at' => Carbon::now(),
                    'deleted_at' => null,
                ],
                [
                    'id' => $calendarTwo->id,
                    'title' => 'Test Calendar 2',
                    'user_id' => $user->id,
                    'created_at' => Carbon::now(),
                    'deleted_at' => null,
                ]
            ]
        ]);
    }

    public function test_401_returned_when_user_not_logged_in()
    {
        $response = $this->getJson('/api/calendars');
        $response->assertStatus(401);
    }
}
