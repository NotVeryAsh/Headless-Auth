<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Calendar::class);

        return response()->json([
            'calendars' => CalendarResource::collection($request->user()->calendars)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(Calendar $calendar): JsonResponse
    {
        $this->authorize('view', $calendar);

        return response()->json([
            'calendar' => new CalendarResource($calendar)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Calendar $calendar): JsonResponse
    {
        $this->authorize('delete', $calendar);

        $calendar->delete();

        return response()->json([], 204);
    }
}
