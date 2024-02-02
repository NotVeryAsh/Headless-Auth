<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CalendarEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Calendar $calendar): Response
    {
        return $user->id->toString() === $calendar->user_id ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CalendarEvent $calendarEvent): Response
    {
        return $user->id->toString() === $calendarEvent->calendar->user_id ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CalendarEvent $calendarEvent): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CalendarEvent $calendarEvent): Response
    {
        return $user->id->toString() === $calendarEvent->calendar->user_id ?
            Response::allow() :
            Response::denyWithStatus(404);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CalendarEvent $calendarEvent): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CalendarEvent $calendarEvent): bool
    {
        //
    }
}
