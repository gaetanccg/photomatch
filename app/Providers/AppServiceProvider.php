<?php

namespace App\Providers;

use App\Events\BookingRequestCreated;
use App\Events\BookingRequestResponded;
use App\Listeners\SendBookingRequestNotificationToPhotographer;
use App\Listeners\SendBookingResponseNotificationToClient;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        Event::listen(
            BookingRequestCreated::class,
            SendBookingRequestNotificationToPhotographer::class
        );

        Event::listen(
            BookingRequestResponded::class,
            SendBookingResponseNotificationToClient::class
        );
    }
}
