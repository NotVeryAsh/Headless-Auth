<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\CreateCalendarRequest;
use App\Http\Requests\Calendar\GetCalendarsRequest;
use App\Http\Requests\Calendar\UpdateCalendarRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(GetCalendarsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Calendar::class);

        $trashed = $request->input('trashed');
        $user = $request->user();

        $calendars = $trashed ?
            $user->calendars()->onlyTrashed()->get() :
            $user->calendars;

        return response()->json([
            'calendars' => CalendarResource::collection($calendars),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(CreateCalendarRequest $request): JsonResponse
    {
        $this->authorize('create', Calendar::class);

        $calendar = $request->user()->calendars()->create($request->validated());

        return response()->json([
            'calendar' => new CalendarResource($calendar),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Calendar $calendar): JsonResponse
    {
        $this->authorize('view', $calendar);

        return response()->json([
            'calendar' => new CalendarResource($calendar),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(UpdateCalendarRequest $request, Calendar $calendar): JsonResponse
    {
        $this->authorize('update', $calendar);

        $calendar->update($request->validated());

        return response()->json([
            'calendar' => new CalendarResource($calendar->fresh()),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function restore(Calendar $calendar): JsonResponse
    {
        $this->authorize('update', $calendar);

        $calendar->restore();

        return response()->json([
            'calendar' => new CalendarResource($calendar->fresh()),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Calendar $calendar): JsonResponse
    {
        $this->authorize('delete', $calendar);

        $calendar->delete();

        return response()->json([
            'calendar' => new CalendarResource($calendar->fresh()),
        ], 200);
    }
}
