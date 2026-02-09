<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    protected $model = Specialty::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Mariage', 'Portrait', 'Événementiel', 'Corporate', 'Mode',
            'Produit', 'Immobilier', 'Sport', 'Nature', 'Architecture',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => 'camera',
        ];
    }
}
