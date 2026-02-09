<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $this->updatePhotographerRating($review);
    }

    public function updated(Review $review): void
    {
        if ($review->isDirty('rating')) {
            $this->updatePhotographerRating($review);
        }
    }

    public function deleted(Review $review): void
    {
        $this->updatePhotographerRating($review);
    }

    private function updatePhotographerRating(Review $review): void
    {
        $photographer = $review->photographer;

        if ($photographer) {
            $avgRating = $photographer->reviews()->avg('rating');
            $totalMissions = $photographer->bookingRequests()->accepted()->count();

            $photographer->update([
                'rating' => $avgRating,
                'total_missions' => $totalMissions,
            ]);
        }
    }
}
