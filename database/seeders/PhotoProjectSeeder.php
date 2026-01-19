<?php

namespace Database\Seeders;

use App\Models\PhotoProject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PhotoProjectSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();

        $locations = [
            'Paris, France',
            'Lyon, France',
            'Marseille, France',
            'Bordeaux, France',
            'Toulouse, France',
            'Nice, France',
            'Nantes, France',
            'Strasbourg, France',
            'Lille, France',
            'Montpellier, France',
        ];

        $projects = [
            // Mariages
            [
                'title' => 'Mariage Sophie & Thomas',
                'description' => 'Nous recherchons un photographe pour notre mariage prévu en été. Cérémonie à l\'église suivie d\'une réception dans un château. Environ 150 invités.',
                'project_type' => 'event',
                'budget_min' => 1500,
                'budget_max' => 2500,
                'estimated_duration' => 10,
            ],
            [
                'title' => 'Mariage intimiste en Provence',
                'description' => 'Mariage champêtre avec 50 personnes. Nous souhaitons des photos naturelles et spontanées dans les vignes.',
                'project_type' => 'event',
                'budget_min' => 800,
                'budget_max' => 1500,
                'estimated_duration' => 6,
            ],
            // Produits
            [
                'title' => 'Shooting produits cosmétiques',
                'description' => 'Besoin de photos professionnelles pour notre nouvelle gamme de cosmétiques bio. 20 produits à photographier.',
                'project_type' => 'product',
                'budget_min' => 500,
                'budget_max' => 1000,
                'estimated_duration' => 4,
            ],
            [
                'title' => 'Photos pour boutique e-commerce',
                'description' => 'Catalogue de vêtements pour notre site e-commerce. 50 articles à photographier sur mannequin.',
                'project_type' => 'product',
                'budget_min' => 1000,
                'budget_max' => 2000,
                'estimated_duration' => 8,
            ],
            // Immobilier
            [
                'title' => 'Photos appartement haussmannien',
                'description' => 'Vente d\'un appartement de 120m² dans le 16ème. Besoin de photos HDR pour l\'annonce.',
                'project_type' => 'real_estate',
                'budget_min' => 200,
                'budget_max' => 400,
                'estimated_duration' => 2,
            ],
            [
                'title' => 'Reportage villa de luxe',
                'description' => 'Villa contemporaine avec piscine et vue mer. Photos intérieures et extérieures pour agence immobilière.',
                'project_type' => 'real_estate',
                'budget_min' => 500,
                'budget_max' => 800,
                'estimated_duration' => 3,
            ],
            // Corporate
            [
                'title' => 'Portraits équipe startup',
                'description' => 'Photos corporate pour notre équipe de 15 personnes. Style moderne et décontracté pour notre site web.',
                'project_type' => 'corporate',
                'budget_min' => 600,
                'budget_max' => 1200,
                'estimated_duration' => 3,
            ],
            [
                'title' => 'Reportage entreprise industrielle',
                'description' => 'Photos de notre usine et de nos équipes pour notre rapport annuel. Mise en valeur des savoir-faire.',
                'project_type' => 'corporate',
                'budget_min' => 800,
                'budget_max' => 1500,
                'estimated_duration' => 5,
            ],
            // Portraits
            [
                'title' => 'Séance famille avec enfants',
                'description' => 'Séance photo en extérieur pour notre famille (2 adultes, 3 enfants). Souhait d\'un style naturel.',
                'project_type' => 'portrait',
                'budget_min' => 150,
                'budget_max' => 300,
                'estimated_duration' => 2,
            ],
            [
                'title' => 'Book mannequin débutant',
                'description' => 'Création d\'un book photo pour débuter dans le mannequinat. 3 tenues, maquillage inclus si possible.',
                'project_type' => 'portrait',
                'budget_min' => 300,
                'budget_max' => 500,
                'estimated_duration' => 3,
            ],
            // Autres
            [
                'title' => 'Couverture festival de musique',
                'description' => 'Besoin d\'un photographe pour couvrir un festival de musique sur 2 jours. Photos des artistes et du public.',
                'project_type' => 'event',
                'budget_min' => 800,
                'budget_max' => 1200,
                'estimated_duration' => 16,
            ],
            [
                'title' => 'Photos culinaires restaurant',
                'description' => 'Nouveau menu à photographier pour notre restaurant gastronomique. 15 plats à mettre en valeur.',
                'project_type' => 'other',
                'budget_min' => 400,
                'budget_max' => 700,
                'estimated_duration' => 4,
            ],
            [
                'title' => 'Anniversaire 50 ans',
                'description' => 'Grande fête pour les 50 ans de ma mère. 80 invités, soirée dansante. Besoin d\'un reportage complet.',
                'project_type' => 'event',
                'budget_min' => 400,
                'budget_max' => 700,
                'estimated_duration' => 5,
            ],
            [
                'title' => 'Shooting grossesse',
                'description' => 'Séance photo grossesse en studio ou extérieur. 7ème mois de grossesse, style épuré et élégant.',
                'project_type' => 'portrait',
                'budget_min' => 200,
                'budget_max' => 400,
                'estimated_duration' => 2,
            ],
            [
                'title' => 'Photos architecture moderne',
                'description' => 'Projet architectural à documenter pour le portfolio d\'un cabinet d\'architectes. Bâtiment tertiaire.',
                'project_type' => 'real_estate',
                'budget_min' => 600,
                'budget_max' => 1000,
                'estimated_duration' => 4,
            ],
        ];

        $statuses = ['draft', 'published', 'in_progress'];
        $statusWeights = [20, 70, 10]; // 20% draft, 70% published, 10% in_progress

        foreach ($projects as $index => $projectData) {
            $client = $clients->random();
            $eventDate = Carbon::now()->addDays(rand(7, 90));

            // Weighted random status
            $rand = rand(1, 100);
            if ($rand <= $statusWeights[0]) {
                $status = $statuses[0];
            } elseif ($rand <= $statusWeights[0] + $statusWeights[1]) {
                $status = $statuses[1];
            } else {
                $status = $statuses[2];
            }

            PhotoProject::create([
                'client_id' => $client->id,
                'title' => $projectData['title'],
                'description' => $projectData['description'],
                'project_type' => $projectData['project_type'],
                'event_date' => $eventDate,
                'date_start' => $eventDate,
                'date_end' => $eventDate->copy()->addDays(rand(0, 2)),
                'location' => $locations[array_rand($locations)],
                'budget_min' => $projectData['budget_min'],
                'budget_max' => $projectData['budget_max'],
                'estimated_duration' => $projectData['estimated_duration'],
                'status' => $status,
            ]);
        }
    }
}
