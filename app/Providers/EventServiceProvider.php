<?php

namespace App\Providers;

use App\Models\StudentsToCourse;
use Illuminate\Support\Facades\Event;
use App\Observers\CourseInvoiceObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        StudentsToCourse::observe(CourseInvoiceObserver::class);

        //
    }
}
