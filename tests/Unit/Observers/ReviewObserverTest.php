<?php

namespace Tests\Unit\Observers;

use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_review_updates_photographer_rating(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        Review::factory()->forBookingRequest($bookingRequest)->withRating(5)->create();

        $photographer->refresh();

        $this->assertEquals('5.0', $photographer->rating);
    }

    public function test_creating_review_calculates_average_rating(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();

        $booking1 = BookingRequest::factory()->forPhotographer($photographer)->accepted()->create();
        $booking2 = BookingRequest::factory()->forPhotographer($photographer)->accepted()->create();

        Review::factory()->forBookingRequest($booking1)->withRating(5)->create();
        Review::factory()->forBookingRequest($booking2)->withRating(3)->create();

        $photographer->refresh();

        $this->assertEquals('4.0', $photographer->rating);
    }

    public function test_creating_review_updates_total_missions(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();

        BookingRequest::factory()->forPhotographer($photographer)->accepted()->count(3)->create();
        BookingRequest::factory()->forPhotographer($photographer)->pending()->count(2)->create();

        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        Review::factory()->forBookingRequest($bookingRequest)->create();

        $photographer->refresh();

        $this->assertEquals(4, $photographer->total_missions);
    }

    public function test_updating_review_rating_updates_photographer(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        $review = Review::factory()
            ->forBookingRequest($bookingRequest)
            ->withRating(3)
            ->create();

        $photographer->refresh();
        $this->assertEquals('3.0', $photographer->rating);

        $review->update(['rating' => 5]);

        $photographer->refresh();
        $this->assertEquals('5.0', $photographer->rating);
    }

    public function test_deleting_review_updates_photographer_rating(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();

        $booking1 = BookingRequest::factory()->forPhotographer($photographer)->accepted()->create();
        $booking2 = BookingRequest::factory()->forPhotographer($photographer)->accepted()->create();

        Review::factory()->forBookingRequest($booking1)->withRating(5)->create();
        $reviewToDelete = Review::factory()->forBookingRequest($booking2)->withRating(3)->create();

        $photographer->refresh();
        $this->assertEquals('4.0', $photographer->rating);

        $reviewToDelete->delete();

        $photographer->refresh();
        $this->assertEquals('5.0', $photographer->rating);
    }

    public function test_deleting_last_review_sets_null_rating(): void
    {
        $photographer = Photographer::factory()->withoutRating()->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        $review = Review::factory()->forBookingRequest($bookingRequest)->create();

        $review->delete();

        $photographer->refresh();
        $this->assertNull($photographer->rating);
    }

    public function test_updating_non_rating_field_does_not_recalculate(): void
    {
        $photographer = Photographer::factory()->create(['rating' => 4.5]);
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        $review = Review::factory()
            ->forBookingRequest($bookingRequest)
            ->withRating(4)
            ->create();

        $review->update(['comment' => 'Updated comment']);

        $photographer->refresh();
        $this->assertEquals('4.0', $photographer->rating);
    }

    public function test_observer_handles_multiple_photographers(): void
    {
        $photographer1 = Photographer::factory()->withoutRating()->create();
        $photographer2 = Photographer::factory()->withoutRating()->create();

        $booking1 = BookingRequest::factory()->forPhotographer($photographer1)->accepted()->create();
        $booking2 = BookingRequest::factory()->forPhotographer($photographer2)->accepted()->create();

        Review::factory()->forBookingRequest($booking1)->withRating(5)->create();
        Review::factory()->forBookingRequest($booking2)->withRating(3)->create();

        $photographer1->refresh();
        $photographer2->refresh();

        $this->assertEquals('5.0', $photographer1->rating);
        $this->assertEquals('3.0', $photographer2->rating);
    }
}
