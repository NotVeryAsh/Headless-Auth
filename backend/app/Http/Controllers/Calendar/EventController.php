<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\CreateEventsRequest;
use App\Http\Requests\Events\GetEventsRequest;
use App\Http\Requests\Events\UpdateEventsRequest;
use App\Http\Resources\EventResource;
use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(GetEventsRequest $request, Calendar $calendar): JsonResponse
    {
        $this->authorize('viewAny', [Event::class, $calendar]);

        $trashed = $request->input('trashed');

        $events = $trashed ?
            $calendar->events()->onlyTrashed()->get() :
            $calendar->events;

        return response()->json([
            'events' => EventResource::collection($events),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(CreateEventsRequest $request, Calendar $calendar): JsonResponse
    {
        $this->authorize('create', [Event::class, $calendar]);

        $event = $calendar->events()->create($request->validated());

        return response()->json([
            'event' => new EventResource($event),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Event $event): JsonResponse
    {
        $this->authorize('view', $event);

        return response()->json([
            'event' => new EventResource($event),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(UpdateEventsRequest $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $event->update($request->validated());

        return response()->json([
            'event' => new EventResource($event->fresh()),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function restore(Event $event): JsonResponse
    {
        $this->authorize('restore', $event);

        $event->restore();

        return response()->json([
            'event' => new EventResource($event->fresh()),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $event->delete();

        return response()->json([
            'event' => new EventResource($event->fresh()),
        ], 200);
    }
}
