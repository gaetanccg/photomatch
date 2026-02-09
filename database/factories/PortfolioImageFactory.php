<?php

namespace Database\Factories;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortfolioImage>
 */
class PortfolioImageFactory extends Factory
{
    protected $model = PortfolioImage::class;

    public function definition(): array
    {
        $filename = fake()->uuid() . '.jpg';

        return [
            'photographer_id' => Photographer::factory(),
            'filename' => $filename,
            'original_name' => fake()->words(3, true) . '.jpg',
            'path' => 'portfolios/1/' . $filename,
            'thumbnail_path' => null,
            'caption' => fake()->optional()->sentence(),
            'specialty_id' => null,
            'sort_order' => fake()->numberBetween(0, 50),
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function withThumbnail(): static
    {
        return $this->state(function (array $attributes) {
            $thumbnailFilename = 'thumb_' . $attributes['filename'];
            return [
                'thumbnail_path' => 'portfolios/1/thumbnails/' . $thumbnailFilename,
            ];
        });
    }

    public function forPhotographer(Photographer $photographer): static
    {
        return $this->state(fn (array $attributes) => [
            'photographer_id' => $photographer->id,
            'path' => 'portfolios/' . $photographer->id . '/' . $attributes['filename'],
        ]);
    }

    public function withSpecialty(Specialty $specialty): static
    {
        return $this->state(fn (array $attributes) => [
            'specialty_id' => $specialty->id,
        ]);
    }
}
