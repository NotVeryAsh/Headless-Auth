<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\CreateCalendarRequest;
use App\Http\Requests\CalendarEvents\CreateCalendarEventsRequest;
use App\Http\Requests\CalendarEvents\GetCalendarEventsRequest;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\CalendarResource;
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
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(CreateCalendarEventsRequest $request, Calendar $calendar): JsonResponse
    {
        $this->authorize('create', [CalendarEvent::class, $calendar]);

        $calendarEvent = $calendar->calendarEvents()->create([
            'title' => $request->validated('title'),
            'start' => $request->validated('start'),
            'end' => $request->validated('end'),
            'all_day' => $request->validated('all_day', true)
        ]);

        return response()->json([
            'calendar_event' => new CalendarEventResource($calendarEvent),
        ], 201);
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

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function restore(CalendarEvent $calendarEvent): JsonResponse
    {
        $this->authorize('restore', $calendarEvent);

        $calendarEvent->restore();

        return response()->json([
            'calendar_event' => new CalendarEventResource($calendarEvent->fresh()),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(CalendarEvent $calendarEvent): JsonResponse
    {
        $this->authorize('delete', $calendarEvent);

        $calendarEvent->delete();

        return response()->json([
            'calendar_events' => new CalendarEventResource($calendarEvent->fresh()),
        ], 200);
    }
}
