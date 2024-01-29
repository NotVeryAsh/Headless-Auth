<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEvent;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(CalendarEvent $calendarEvent): JsonResponse
    {
        $this->authorize('view', $calendarEvent);

        return response()->json([
            'calendar_event' => new CalendarEventResource($calendarEvent)
        ]);
    }
}
