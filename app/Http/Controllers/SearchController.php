<?php

namespace App\Http\Controllers;

use App\Models\PhotoProject;
use App\Models\Photographer;
use App\Models\Specialty;
use App\Services\PhotographerMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private PhotographerMatchingService $matchingService
    ) {}

    private function getCachedSpecialties()
    {
        return Cache::remember('specialties:all', 86400, fn() => Specialty::all());
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'specialty_ids',
            'location',
            'min_budget',
            'max_budget',
            'available_date',
            'min_rating'
        ]);

        $sortBy = $request->get('sort', 'rating');
        $sortDir = $request->get('dir', 'desc');

        // Validate sort options
        $allowedSorts = ['rating', 'hourly_rate', 'experience_years', 'total_missions'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'rating';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $project = null;
        $useMatching = false;

        // If project_id provided: use full matching algorithm
        if ($projectId = $request->get('project_id')) {
            $project = PhotoProject::findOrFail($projectId);

            // Verify ownership if authenticated
            if (auth()->check() && auth()->id() !== $project->client_id) {
                abort(403);
            }

            $photographers = $this->matchingService
                ->findMatchingPhotographers($project, $filters);

            $useMatching = true;

            // Prepare map data for matching results
            $mapPhotographers = $photographers->getCollection()
                ->filter(fn($p) => $p->latitude && $p->longitude)
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->user->name,
                    'location' => $p->location,
                    'lat' => (float) $p->latitude,
                    'lng' => (float) $p->longitude,
                    'rating' => $p->rating,
                    'hourly_rate' => $p->hourly_rate,
                    'photo' => $p->portfolioImages->first()?->url ?? $p->user->profile_photo_url ?? null,
                    'url' => route('photographers.show', $p),
                ])
                ->values();

            return view('search.index', [
                'photographers' => $photographers,
                'project' => $project,
                'filters' => $filters,
                'specialties' => $this->getCachedSpecialties(),
                'useMatching' => $useMatching,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir,
                'mapPhotographers' => $mapPhotographers,
            ]);
        }

        // Otherwise: classic search with manual filters
        $photographers = Photographer::query()
            ->verified()
            ->whereHas('user')
            ->when($filters['specialty_ids'] ?? null, function ($q, $ids) {
                $ids = is_array($ids) ? $ids : [$ids];
                $q->whereHas('specialties', fn($sq) => $sq->whereIn('specialties.id', $ids));
            })
            ->when($filters['location'] ?? null, function ($q, $location) {
                $q->nearLocation($location);
            })
            ->when($filters['max_budget'] ?? null, function ($q, $max) {
                $q->where('hourly_rate', '<=', $max);
            })
            ->when($filters['min_budget'] ?? null, function ($q, $min) {
                $q->where('hourly_rate', '>=', $min);
            })
            ->when($filters['available_date'] ?? null, function ($q, $date) {
                $q->availableOn($date);
            })
            ->when($filters['min_rating'] ?? null, function ($q, $rating) {
                $q->minRating($rating);
            })
            ->with(['user', 'specialties', 'reviews', 'portfolioImages' => fn($q) => $q->featured()->limit(1)])
            ->withCount('reviews')
            ->orderBy($sortBy, $sortDir)
            ->paginate(12)
            ->withQueryString();

        // Prepare map data
        $mapPhotographers = $photographers->getCollection()
            ->filter(fn($p) => $p->latitude && $p->longitude)
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->user->name,
                'location' => $p->location,
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'rating' => $p->rating,
                'hourly_rate' => $p->hourly_rate,
                'photo' => $p->portfolioImages->first()?->url ?? $p->user->profile_photo_url ?? null,
                'url' => route('photographers.show', $p),
            ])
            ->values();

        return view('search.index', [
            'photographers' => $photographers,
            'project' => $project,
            'filters' => $filters,
            'specialties' => $this->getCachedSpecialties(),
            'useMatching' => $useMatching,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'mapPhotographers' => $mapPhotographers,
        ]);
    }
}
