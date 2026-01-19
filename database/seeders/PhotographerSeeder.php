<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Photographer;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PhotographerSeeder extends Seeder
{
    public function run(): void
    {
        $photographerUsers = User::where('role', 'photographer')->get();
        $specialties = Specialty::all();

        $bios = [
            'Photographe passionné avec un oeil pour les détails. Spécialisé dans la capture de moments uniques.',
            'Créatif et professionnel, je m\'efforce de raconter votre histoire à travers mes images.',
            'Fort de plusieurs années d\'expérience, je propose des prestations sur mesure adaptées à vos besoins.',
            'Mon objectif : capturer l\'essence de chaque instant avec sensibilité et professionnalisme.',
            'Photographe autodidacte devenu professionnel, je mets ma passion au service de vos projets.',
            'Artiste dans l\'âme, je cherche toujours l\'angle parfait pour sublimer vos souvenirs.',
            'Spécialiste de la lumière naturelle, je crée des images authentiques et intemporelles.',
            'Photographe dynamique et créatif, toujours à la recherche de nouvelles perspectives.',
            'Passionné par la photographie depuis l\'enfance, je transforme vos moments en souvenirs éternels.',
            'Expert en post-production, je livre des images soignées et professionnelles.',
            'Photographe polyvalent capable de s\'adapter à tous types de projets.',
        ];

        $experienceLevels = ['beginner', 'intermediate', 'expert'];

        $locations = [
            ['Paris, France', 48.8566, 2.3522],
            ['Lyon, France', 45.7640, 4.8357],
            ['Marseille, France', 43.2965, 5.3698],
            ['Bordeaux, France', 44.8378, -0.5792],
            ['Lille, France', 50.6292, 3.0573],
            ['Toulouse, France', 43.6047, 1.4442],
            ['Nice, France', 43.7102, 7.2620],
            ['Nantes, France', 47.2184, -1.5536],
            ['Strasbourg, France', 48.5734, 7.7521],
            ['Montpellier, France', 43.6108, 3.8767],
            ['Rennes, France', 48.1173, -1.6778],
        ];

        foreach ($photographerUsers as $index => $user) {
            $locationData = $locations[$index % count($locations)];

            $photographer = Photographer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $bios[$index % count($bios)],
                    'experience_years' => rand(1, 15),
                    'portfolio_url' => 'https://portfolio.example.com/' . strtolower(str_replace(' ', '-', $user->name)),
                    'hourly_rate' => rand(50, 150),
                    'daily_rate' => rand(300, 1000),
                    'is_verified' => rand(0, 1) === 1,
                    'rating' => rand(0, 1) === 1 ? rand(35, 50) / 10 : null,
                    'total_missions' => rand(0, 50),
                    'location' => $locationData[0],
                    'latitude' => $locationData[1],
                    'longitude' => $locationData[2],
                ]
            );

            // Attach 2-4 random specialties
            $numSpecialties = rand(2, 4);
            $randomSpecialties = $specialties->random($numSpecialties);

            foreach ($randomSpecialties as $specialty) {
                if (!$photographer->specialties()->where('specialty_id', $specialty->id)->exists()) {
                    $photographer->specialties()->attach($specialty->id, [
                        'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                    ]);
                }
            }

            // Create 10-20 availabilities over the next 60 days
            $numAvailabilities = rand(10, 20);
            $usedDates = [];

            for ($i = 0; $i < $numAvailabilities; $i++) {
                $daysToAdd = rand(1, 60);
                $date = Carbon::now()->addDays($daysToAdd)->format('Y-m-d');

                if (in_array($date, $usedDates)) {
                    continue;
                }
                $usedDates[] = $date;

                Availability::firstOrCreate(
                    [
                        'photographer_id' => $photographer->id,
                        'date' => $date,
                    ],
                    [
                        'is_available' => rand(1, 10) <= 8, // 80% available
                        'note' => rand(0, 1) === 1 ? null : 'Disponible sur demande',
                    ]
                );
            }
        }
    }
}
