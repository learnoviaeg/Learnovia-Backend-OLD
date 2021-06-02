<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,    
        ],
        
        // 'App\Events\UserGradeEvent' => [
        //     'App\Listeners\UserGradeListener',
        // ],

        'App\Events\UserItemDetailsEvent' => [
            'App\Listeners\CreateUserItemDetailsListener',
        ],

        'App\Events\MassLogsEvent' => [
            'App\Listeners\MassLogsListener',
        ],

        'App\Events\GradeItemEvent' => [
            'App\Listeners\ItemDetailslistener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
