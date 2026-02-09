<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'booking_request_id' => BookingRequest::factory()->accepted(),
            'client_id' => User::factory()->client(),
            'photographer_id' => Photographer::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'photographer_response' => null,
            'responded_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withResponse(): static
    {
        return $this->state(fn (array $attributes) => [
            'photographer_response' => fake()->paragraph(),
            'responded_at' => now(),
        ]);
    }

    public function withRating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }

    public function forBookingRequest(BookingRequest $bookingRequest): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $bookingRequest->project->client_id,
            'photographer_id' => $bookingRequest->photographer_id,
        ]);
    }
}
