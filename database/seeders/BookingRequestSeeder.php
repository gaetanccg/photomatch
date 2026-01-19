<?php

namespace Database\Seeders;

use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingRequestSeeder extends Seeder
{
    public function run(): void
    {
        $publishedProjects = PhotoProject::where('status', 'published')->get();
        $photographers = Photographer::all();

        $clientMessages = [
            'Bonjour, votre profil m\'intéresse beaucoup. Seriez-vous disponible pour ce projet ?',
            'J\'ai vu votre portfolio et j\'adore votre style ! Pouvons-nous discuter de ce projet ?',
            'Nous recherchons un photographe professionnel et votre expérience semble correspondre à nos attentes.',
            'Votre travail est remarquable. Seriez-vous intéressé par cette mission ?',
            'Bonjour, nous organisons cet événement et pensons que vous seriez le photographe idéal.',
            'J\'aimerais en savoir plus sur vos disponibilités et tarifs pour ce projet.',
            'Votre spécialisation correspond parfaitement à notre besoin. Pouvez-vous me contacter ?',
            'Recommandé par un ami, j\'aimerais vous confier ce projet photo.',
        ];

        $photographerResponses = [
            'Merci pour votre demande ! Je suis très intéressé par ce projet. Je suis disponible aux dates indiquées.',
            'Bonjour, merci de m\'avoir contacté. Ce projet me plaît beaucoup, discutons des détails.',
            'Je vous remercie pour cette proposition. Je serais ravi de collaborer avec vous sur ce projet.',
            'Merci pour votre confiance. Je suis disponible et prêt à commencer dès que possible.',
        ];

        $declinedResponses = [
            'Merci pour votre demande, malheureusement je ne suis pas disponible à ces dates.',
            'Je vous remercie de m\'avoir contacté, mais ce type de projet ne correspond pas à mes spécialités.',
            'Désolé, mon planning est complet pour cette période. Je vous souhaite de trouver le photographe idéal.',
        ];

        $statuses = ['pending', 'accepted', 'declined'];
        $statusWeights = [60, 30, 10]; // 60% pending, 30% accepted, 10% declined

        $requestCount = 0;
        $targetRequests = rand(20, 30);

        foreach ($publishedProjects as $project) {
            if ($requestCount >= $targetRequests) {
                break;
            }

            // 1-3 requests per project
            $numRequests = rand(1, 3);
            $usedPhotographers = [];

            for ($i = 0; $i < $numRequests; $i++) {
                if ($requestCount >= $targetRequests) {
                    break;
                }

                // Get a random photographer not already requested for this project
                $availablePhotographers = $photographers->whereNotIn('id', $usedPhotographers);
                if ($availablePhotographers->isEmpty()) {
                    break;
                }

                $photographer = $availablePhotographers->random();
                $usedPhotographers[] = $photographer->id;

                // Weighted random status
                $rand = rand(1, 100);
                if ($rand <= $statusWeights[0]) {
                    $status = $statuses[0];
                } elseif ($rand <= $statusWeights[0] + $statusWeights[1]) {
                    $status = $statuses[1];
                } else {
                    $status = $statuses[2];
                }

                $sentAt = Carbon::now()->subDays(rand(1, 30));
                $respondedAt = null;
                $photographerResponse = null;
                $proposedPrice = null;

                if ($status === 'accepted') {
                    $respondedAt = $sentAt->copy()->addDays(rand(1, 5));
                    $photographerResponse = $photographerResponses[array_rand($photographerResponses)];
                    $proposedPrice = rand(
                        (int) $project->budget_min,
                        (int) $project->budget_max
                    );
                } elseif ($status === 'declined') {
                    $respondedAt = $sentAt->copy()->addDays(rand(1, 3));
                    $photographerResponse = $declinedResponses[array_rand($declinedResponses)];
                }

                BookingRequest::create([
                    'project_id' => $project->id,
                    'photographer_id' => $photographer->id,
                    'status' => $status,
                    'client_message' => $clientMessages[array_rand($clientMessages)],
                    'photographer_response' => $photographerResponse,
                    'proposed_price' => $proposedPrice,
                    'sent_at' => $sentAt,
                    'responded_at' => $respondedAt,
                ]);

                $requestCount++;
            }
        }
    }
}
