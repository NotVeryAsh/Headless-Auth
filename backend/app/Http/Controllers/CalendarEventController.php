<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    public function index(): JsonResponse
    {
        // sample data
        $eventOne = (object) [
            'id' => 1,
            'title' => 'Event One',
            'allDay' => true,
            'start' => Carbon::now()->subWeek(),
            'end' => Carbon::now(),
        ];

        $eventTwo = (object) [
            'id' => 2,
            'title' => 'Event Two',
            'allDay' => true,
            'start' => Carbon::now()->addDays(4),
            'end' => Carbon::now()->addWeek(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Event List',
            'events' => collect([$eventOne, $eventTwo])
        ], 200);
    }
}
