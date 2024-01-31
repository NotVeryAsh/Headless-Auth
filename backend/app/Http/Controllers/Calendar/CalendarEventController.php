<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarEvents\GetCalendarEventsRequest;
use App\Http\Resources\CalendarEventResource;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(GetCalendarEventsRequest $request, Calendar $calendar): JsonResponse
    {
        $this->authorize('viewAny', [CalendarEvent::class, $calendar]);

        $trashed = $request->input('trashed');

        $calendarEvents = $trashed ?
            $calendar->calendarEvents()->onlyTrashed()->get() :
            $calendar->calendarEvents;

        return response()->json([
            'calendar_events' => CalendarEventResource::collection($calendarEvents),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(CalendarEvent $calendarEvent): JsonResponse
    {
        $this->authorize('view', $calendarEvent);

        return response()->json([
            'calendar_event' => new CalendarEventResource($calendarEvent),
        ]);
    }
}
