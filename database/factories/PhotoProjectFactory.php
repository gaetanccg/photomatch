<?php

namespace Database\Factories;

use App\Enums\ProjectType;
use App\Models\PhotoProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotoProject>
 */
class PhotoProjectFactory extends Factory
{
    protected $model = PhotoProject::class;

    public function definition(): array
    {
        $dateStart = fake()->dateTimeBetween('+1 week', '+2 months');
        $dateEnd = (clone $dateStart)->modify('+1 day');

        return [
            'client_id' => User::factory()->client(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(2, true),
            'project_type' => fake()->randomElement(ProjectType::cases()),
            'event_date' => $dateStart,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'location' => fake()->city().', France',
            'latitude' => fake()->latitude(42, 51),
            'longitude' => fake()->longitude(-5, 8),
            'budget_min' => fake()->randomFloat(2, 100, 500),
            'budget_max' => fake()->randomFloat(2, 500, 2000),
            'estimated_duration' => fake()->numberBetween(2, 10),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function forClient(User $client): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => $client->id,
        ]);
    }

    public function ofType(ProjectType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'project_type' => $type,
        ]);
    }
}
