<?php

namespace Tests\Feature\Calendar;

use App\Models\Calendar;
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

        $calendarOne = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $calendarTwo = Calendar::factory()->create([
            'title' => 'Test Calendar 2',
        ]);

        // Test Calendar 3 should not be returned since it is trashed
        $calendarThree = Calendar::factory()->create([
            'title' => 'Test Calendar 3',
        ]);

        $calendarThree->delete();

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
                ],
            ],
        ]);
    }

    public function test_can_get_trashed_calendars()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $calendarOne = Calendar::factory()->create([
            'title' => 'Test Calendar',
        ]);

        $calendarOne->delete();

        // Test Calendar 2 should not be returned since it is not trashed
        Calendar::factory()->create([
            'title' => 'Test Calendar 2',
        ]);

        $response = $this->getJson('/api/calendars?trashed=1');

        $response->assertExactJson([
            'calendars' => [
                [
                    'id' => $calendarOne->id,
                    'title' => 'Test Calendar',
                    'user_id' => $user->id,
                    'created_at' => Carbon::now(),
                    'deleted_at' => Carbon::now(),
                ],
            ],
        ]);
    }

    public function test_trashed_must_be_a_boolean()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/calendars?trashed=test');

        $response->assertExactJson([
            'message' => 'The trashed field must be true or false.',
            'errors' => [
                'trashed' => [
                    'The trashed field must be true or false.',
                ],
            ],
        ]);
    }

    public function test_404_returned_when_user_not_logged_in()
    {
        $response = $this->getJson('/api/calendars');
        $response->assertStatus(404);
    }
}
