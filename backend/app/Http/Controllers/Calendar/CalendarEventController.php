<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class CalendarEventController extends Controller
{
    public function index(/*GetCalendarEventsRequest $request, Calendar $calendar*/): JsonResponse
    {
        // TODO Authorize user

        // TODO Write tests
        // TODO Needs better url -> calendar/*calendar*/events then see if user has permission

/*        $user = $request->user();

        $start = $request->validated('start', Carbon::now()->startOfMonth());
        $end = $request->validated('end', Carbon::now()->endOfMonth());

        $events = $user->calendarEvents()
            ->whereDuring($start, $end)
            ->orderBy('start', 'asc')
            ->get();

        return response()->json([
            'events' => CalendarEventResource::collection($events)
        ], 200);*/
    }
}
