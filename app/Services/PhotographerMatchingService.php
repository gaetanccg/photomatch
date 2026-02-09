<?php

namespace App\Services;

use App\Models\Photographer;
use App\Models\PhotoProject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PhotographerMatchingService
{
    // Pondérations des critères (total = 100)
    private const WEIGHT_SPECIALTY = 25;

    private const WEIGHT_KEYWORDS = 15;

    private const WEIGHT_DISTANCE = 15;

    private const WEIGHT_RATING = 20;

    private const WEIGHT_EXPERIENCE = 10;

    private const WEIGHT_PRICE = 15;

    private const MAX_DISTANCE_KM = 50; // Distance max pour le scoring

    /**
     * Find matching photographers for a project with scoring.
     */
    public function findMatchingPhotographers(
        PhotoProject $project,
        array $filters = [],
        int $perPage = 12
    ): LengthAwarePaginator {
        $query = Photographer::query()
            ->whereHas('user')
            ->with(['user', 'specialties', 'portfolioImages' => fn ($q) => $q->featured()->limit(1)]);

        // Apply strict filters
        $this->applyStrictFilters($query, $project, $filters);

        // Get all matching photographers
        $photographers = $query->get();

        // Calculate scores for each photographer
        $scoredPhotographers = $photographers->map(function ($photographer) use ($project) {
            $photographer->matching_score = $this->calculateScore($photographer, $project);

            return $photographer;
        });

        // Sort by total score descending
        $sorted = $scoredPhotographers->sortByDesc(fn ($p) => $p->matching_score['total']);

        // Manual pagination
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $items = $sorted->slice($offset, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $sorted->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Apply strict (eliminatory) filters via SQL.
     */
    public function applyStrictFilters(Builder $query, PhotoProject $project, array $filters): void
    {
        // Must be verified
        $query->verified();

        // Filter by specialty matching project type
        if ($project->project_type) {
            $query->whereHas('specialties', function ($sq) use ($project) {
                $sq->where('slug', $project->project_type);
            });
        }

        // Filter by availability on event date
        if ($project->event_date) {
            $query->whereHas('availabilities', function ($aq) use ($project) {
                $aq->where('date', $project->event_date)
                    ->where('is_available', true);
            });
        }

        // Filter by budget max (photographer rate must be within budget)
        if ($project->budget_max) {
            $query->where('hourly_rate', '<=', $project->budget_max);
        }

        // Additional filters from user
        if (! empty($filters['specialty_ids'])) {
            $query->whereHas('specialties', function ($sq) use ($filters) {
                $sq->whereIn('specialties.id', (array) $filters['specialty_ids']);
            });
        }

        if (! empty($filters['max_budget'])) {
            $query->where('hourly_rate', '<=', $filters['max_budget']);
        }

        if (! empty($filters['min_budget'])) {
            $query->where('hourly_rate', '>=', $filters['min_budget']);
        }

        if (! empty($filters['available_date'])) {
            $query->whereHas('availabilities', function ($aq) use ($filters) {
                $aq->where('date', $filters['available_date'])
                    ->where('is_available', true);
            });
        }

        if (! empty($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (! empty($filters['location'])) {
            $query->where('location', 'LIKE', '%'.$filters['location'].'%');
        }
    }

    /**
     * Calculate the weighted score for a photographer.
     */
    public function calculateScore(Photographer $photographer, PhotoProject $project): array
    {
        $specialtyScore = $this->calculateSpecialtyScore($photographer, $project);
        $keywordScore = $this->calculateKeywordScore($photographer, $project);
        $distanceScore = $this->calculateDistanceScore($photographer, $project);
        $ratingScore = $this->calculateRatingScore($photographer);
        $experienceScore = $this->calculateExperienceScore($photographer);
        $priceScore = $this->calculatePriceScore($photographer, $project);

        return [
            'total' => round($specialtyScore + $keywordScore + $distanceScore + $ratingScore + $experienceScore + $priceScore, 1),
            'breakdown' => [
                'specialty' => round($specialtyScore, 1),
                'keywords' => round($keywordScore, 1),
                'distance' => round($distanceScore, 1),
                'rating' => round($ratingScore, 1),
                'experience' => round($experienceScore, 1),
                'price' => round($priceScore, 1),
            ],
        ];
    }

    /**
     * Calculate specialty match score.
     */
    public function calculateSpecialtyScore(Photographer $photographer, PhotoProject $project): float
    {
        if (! $project->project_type) {
            return self::WEIGHT_SPECIALTY; // No type specified = full score
        }

        $matchingSpecialty = $photographer->specialties
            ->firstWhere('slug', $project->project_type);

        if (! $matchingSpecialty) {
            return 0;
        }

        $experienceLevel = $matchingSpecialty->pivot->experience_level ?? 'beginner';

        return match ($experienceLevel) {
            'expert' => self::WEIGHT_SPECIALTY,
            'intermediate' => self::WEIGHT_SPECIALTY - 5,
            'beginner' => self::WEIGHT_SPECIALTY - 10,
            default => self::WEIGHT_SPECIALTY - 10,
        };
    }

    /**
     * Calculate keyword match score.
     */
    public function calculateKeywordScore(Photographer $photographer, PhotoProject $project): float
    {
        if (! $photographer->keywords || ! $project->description) {
            return self::WEIGHT_KEYWORDS * 0.5; // Score neutre si pas de mots-clés ou pas de description
        }

        $keywords = array_map('trim', explode(',', strtolower($photographer->keywords)));
        $description = strtolower($project->description.' '.$project->title);

        $matches = 0;
        foreach ($keywords as $keyword) {
            if (! empty($keyword) && str_contains($description, $keyword)) {
                $matches++;
            }
        }

        $matchRatio = min($matches / max(count($keywords), 1), 1);

        return $matchRatio * self::WEIGHT_KEYWORDS;
    }

    /**
     * Calculate distance score using Haversine formula.
     */
    public function calculateDistanceScore(Photographer $photographer, PhotoProject $project): float
    {
        // If no coordinates, return full score (cannot penalize)
        if (! $photographer->latitude || ! $photographer->longitude ||
            ! $project->latitude || ! $project->longitude) {
            return self::WEIGHT_DISTANCE;
        }

        $distance = $this->haversineDistance(
            $photographer->latitude,
            $photographer->longitude,
            $project->latitude,
            $project->longitude
        );

        // Score inversely proportional to distance
        return max(0, self::WEIGHT_DISTANCE * (1 - $distance / self::MAX_DISTANCE_KM));
    }

    /**
     * Calculate rating score using Wilson Score approach.
     * Takes into account both rating quality AND quantity of reviews.
     */
    public function calculateRatingScore(Photographer $photographer): float
    {
        $reviewsCount = $photographer->reviews()->count();
        $avgRating = $photographer->getRawOriginal('rating');

        if ($reviewsCount === 0 || ! $avgRating) {
            return self::WEIGHT_RATING * 0.3; // Pénalité pour nouveaux photographes
        }

        // Wilson Score simplifié
        // Plus d'avis = plus de confiance dans la note
        $confidence = 1 - (1 / (1 + $reviewsCount * 0.1)); // 0→1 selon nb avis

        // Score de base (note sur 5)
        $ratingScore = $avgRating / 5;

        // Combinaison : note × confiance, avec bonus pour volume
        $volumeBonus = min($reviewsCount / 20, 0.2); // Max 20% bonus à 20+ avis

        $finalScore = ($ratingScore * $confidence + $volumeBonus) * self::WEIGHT_RATING;

        return min($finalScore, self::WEIGHT_RATING); // Cap au max
    }

    /**
     * Calculate experience score based on years and missions.
     */
    public function calculateExperienceScore(Photographer $photographer): float
    {
        // Cap at 10 years
        $yearsScore = min($photographer->experience_years / 10, 1);

        // Cap at 50 missions
        $missionsScore = min($photographer->total_missions / 50, 1);

        return (($yearsScore + $missionsScore) / 2) * self::WEIGHT_EXPERIENCE;
    }

    /**
     * Calculate price compatibility score.
     */
    public function calculatePriceScore(Photographer $photographer, PhotoProject $project): float
    {
        // No budget specified = full score
        if (! $project->budget_max) {
            return self::WEIGHT_PRICE;
        }

        $budgetMin = $project->budget_min ?? 0;
        $budgetMiddle = ($budgetMin + $project->budget_max) / 2;

        $priceGap = abs($photographer->hourly_rate - $budgetMiddle);
        $maxGap = $project->budget_max - $budgetMin;

        return max(0, self::WEIGHT_PRICE * (1 - $priceGap / max($maxGap, 1)));
    }

    /**
     * Calculate distance between two GPS points in km using Haversine formula.
     */
    public function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
