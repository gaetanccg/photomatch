<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    protected $model = BookingRequest::class;

    public function definition(): array
    {
        return [
            'project_id' => PhotoProject::factory(),
            'photographer_id' => Photographer::factory(),
            'status' => BookingStatus::Pending,
            'client_message' => fake()->paragraph(),
            'photographer_response' => null,
            'proposed_price' => fake()->randomFloat(2, 100, 1500),
            'sent_at' => now(),
            'responded_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Pending,
            'responded_at' => null,
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Accepted,
            'photographer_response' => fake()->paragraph(),
            'responded_at' => now(),
        ]);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Declined,
            'photographer_response' => fake()->paragraph(),
            'responded_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Cancelled,
        ]);
    }

    public function forProject(PhotoProject $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
        ]);
    }

    public function forPhotographer(Photographer $photographer): static
    {
        return $this->state(fn (array $attributes) => [
            'photographer_id' => $photographer->id,
        ]);
    }
}
