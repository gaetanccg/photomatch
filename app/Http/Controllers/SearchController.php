<?php

namespace App\Http\Controllers;

use App\Models\PhotoProject;
use App\Models\Specialty;
use App\Queries\PhotographerSearchQuery;
use App\Services\PhotographerMatchingService;
use App\Transformers\PhotographerMapTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        private PhotographerMatchingService $matchingService,
        private PhotographerMapTransformer $mapTransformer
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

        $sortBy = $this->validateSort($request->get('sort', 'rating'));
        $sortDir = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $project = null;
        $useMatching = false;

        if ($projectId = $request->get('project_id')) {
            return $this->searchWithMatching($projectId, $filters, $sortBy, $sortDir);
        }

        $photographers = (new PhotographerSearchQuery($filters))
            ->applyFilters()
            ->withRelations()
            ->sortBy($sortBy, $sortDir)
            ->paginate(12);

        $mapPhotographers = $this->mapTransformer->transformCollection(
            $photographers->getCollection()
        );

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

    private function searchWithMatching(int $projectId, array $filters, string $sortBy, string $sortDir): View
    {
        $project = PhotoProject::findOrFail($projectId);

        if (auth()->check() && auth()->id() !== $project->client_id) {
            abort(403);
        }

        $photographers = $this->matchingService
            ->findMatchingPhotographers($project, $filters);

        $mapPhotographers = $this->mapTransformer->transformCollection(
            $photographers->getCollection()
        );

        return view('search.index', [
            'photographers' => $photographers,
            'project' => $project,
            'filters' => $filters,
            'specialties' => $this->getCachedSpecialties(),
            'useMatching' => true,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'mapPhotographers' => $mapPhotographers,
        ]);
    }

    private function validateSort(string $sort): string
    {
        $allowedSorts = ['rating', 'hourly_rate', 'experience_years', 'total_missions'];
        return in_array($sort, $allowedSorts) ? $sort : 'rating';
    }
}
