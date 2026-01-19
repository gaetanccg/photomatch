<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Mariage',
                'slug' => 'wedding',
                'description' => 'Photographie de mariage et cérémonies',
                'icon' => 'heart',
            ],
            [
                'name' => 'Événementiel',
                'slug' => 'event',
                'description' => 'Couverture d\'événements professionnels et privés',
                'icon' => 'calendar',
            ],
            [
                'name' => 'Produit',
                'slug' => 'product',
                'description' => 'Photographie de produits pour e-commerce et catalogues',
                'icon' => 'shopping-bag',
            ],
            [
                'name' => 'Immobilier',
                'slug' => 'real-estate',
                'description' => 'Photographie immobilière et architecture',
                'icon' => 'home',
            ],
            [
                'name' => 'Entreprise',
                'slug' => 'corporate',
                'description' => 'Portraits professionnels et photos d\'entreprise',
                'icon' => 'briefcase',
            ],
            [
                'name' => 'Portrait',
                'slug' => 'portrait',
                'description' => 'Portraits individuels et de famille',
                'icon' => 'user',
            ],
            [
                'name' => 'Culinaire',
                'slug' => 'food',
                'description' => 'Photographie culinaire et gastronomique',
                'icon' => 'utensils',
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(
                ['slug' => $specialty['slug']],
                $specialty
            );
        }
    }
}
