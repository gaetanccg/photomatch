<?php

namespace Database\Factories;

use App\Models\Photographer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photographer>
 */
class PhotographerFactory extends Factory
{
    protected $model = Photographer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->photographer(),
            'siret' => fake()->numerify('##############'),
            'bio' => fake()->paragraphs(2, true),
            'keywords' => implode(', ', fake()->words(5)),
            'experience_years' => fake()->numberBetween(1, 20),
            'portfolio_url' => fake()->url(),
            'hourly_rate' => fake()->randomFloat(2, 50, 300),
            'daily_rate' => fake()->randomFloat(2, 400, 2000),
            'is_verified' => true,
            'rating' => fake()->randomFloat(1, 3.0, 5.0),
            'total_missions' => fake()->numberBetween(0, 100),
            'location' => fake()->city().', France',
            'latitude' => fake()->latitude(42, 51),
            'longitude' => fake()->longitude(-5, 8),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => false,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }

    public function withoutRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => null,
            'total_missions' => 0,
        ]);
    }

    public function withUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
