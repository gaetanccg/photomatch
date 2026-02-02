<?php

namespace App\Providers;

use App\Events\BookingRequestCreated;
use App\Events\BookingRequestResponded;
use App\Listeners\SendBookingRequestNotificationToPhotographer;
use App\Listeners\SendBookingResponseNotificationToClient;
use App\Models\PortfolioImage;
use App\Models\Review;
use App\Observers\PortfolioImageObserver;
use App\Observers\ReviewObserver;
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
        // Register observers
        PortfolioImage::observe(PortfolioImageObserver::class);
        Review::observe(ReviewObserver::class);

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
