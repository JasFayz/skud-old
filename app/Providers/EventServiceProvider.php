<?php

namespace App\Providers;

use App\Observers\GuestObserver;
use App\Observers\TerminalObserver;
use App\Observers\TerminalRequestLogObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Booking\Entities\Booking;
use Modules\Booking\Observers\BookingObserver;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\User\Entities\User;
use Modules\Visitor\Entities\Guest;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        User::class => [UserObserver::class],
        Guest::class => [GuestObserver::class],
        Booking::class => [BookingObserver::class],
        Terminal::class => [TerminalObserver::class],
        TerminalRequestLog::class => [TerminalRequestLogObserver::class]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
