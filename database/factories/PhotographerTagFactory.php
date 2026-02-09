<?php

namespace Database\Factories;

use App\Models\Photographer;
use App\Models\PhotographerTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographerTag>
 */
class PhotographerTagFactory extends Factory
{
    protected $model = PhotographerTag::class;

    public function definition(): array
    {
        return [
            'photographer_id' => Photographer::factory(),
            'name' => fake()->unique()->word(),
        ];
    }

    public function forPhotographer(Photographer $photographer): static
    {
        return $this->state(fn (array $attributes) => [
            'photographer_id' => $photographer->id,
        ]);
    }
}
