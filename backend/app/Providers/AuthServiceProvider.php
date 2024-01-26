<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Policies\CalendarPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Calendar::class => CalendarPolicy::class,
        CalendarEvent::class => CalendarPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
