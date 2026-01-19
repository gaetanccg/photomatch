<?php

namespace App\Http\Controllers;

use App\Models\PhotoProject;
use App\Models\Photographer;
use App\Models\Specialty;
use App\Services\PhotographerMatchingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private PhotographerMatchingService $matchingService
    ) {}

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

            return view('search.index', [
                'photographers' => $photographers,
                'project' => $project,
                'filters' => $filters,
                'specialties' => Specialty::all(),
                'useMatching' => $useMatching,
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
                $q->where('location', 'LIKE', "%{$location}%");
            })
            ->when($filters['max_budget'] ?? null, function ($q, $max) {
                $q->where('hourly_rate', '<=', $max);
            })
            ->when($filters['min_budget'] ?? null, function ($q, $min) {
                $q->where('hourly_rate', '>=', $min);
            })
            ->when($filters['available_date'] ?? null, function ($q, $date) {
                $q->whereHas('availabilities', fn($aq) =>
                    $aq->where('date', $date)->where('is_available', true)
                );
            })
            ->when($filters['min_rating'] ?? null, function ($q, $rating) {
                $q->where('rating', '>=', $rating);
            })
            ->with(['user', 'specialties'])
            ->orderByDesc('rating')
            ->paginate(12)
            ->withQueryString();

        return view('search.index', [
            'photographers' => $photographers,
            'project' => $project,
            'filters' => $filters,
            'specialties' => Specialty::all(),
            'useMatching' => $useMatching,
        ]);
    }
}
