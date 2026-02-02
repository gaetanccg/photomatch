<?php

namespace App\Http\Controllers;

use App\Enums\ProjectType;
use App\Http\Requests\StorePhotoProjectRequest;
use App\Http\Requests\UpdatePhotoProjectRequest;
use App\Models\PhotoProject;
use App\Models\Photographer;
use App\Models\Specialty;
use App\Services\PhotographerMatchingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PhotoProjectController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', PhotoProject::class);
        $projects = auth()->user()->photoProjects()
                          ->withCount('bookingRequests')
                          ->latest()
                          ->paginate(10);

        return view('client.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', PhotoProject::class);

        $specialties = Specialty::all();
        $projectTypes = ProjectType::options();

        return view('client.projects.create', compact('specialties', 'projectTypes'));
    }

    public function store(StorePhotoProjectRequest $request): RedirectResponse
    {
        $this->authorize('create', PhotoProject::class);

        $data = $request->validated();
        $data['client_id'] = auth()->id();
        $data['status'] = $request->input('status', 'draft');

        $project = PhotoProject::create($data);

        return redirect()
            ->route('client.projects.show', $project)
            ->with('success', 'Projet créé avec succès.');
    }

    public function show(PhotoProject $project, PhotographerMatchingService $matchingService): View
    {
        $this->authorize('view', $project);

        $project->load([
            'bookingRequests' => function ($query) {
                $query->with(['photographer.user'])->latest();
            },
        ]);

        $matchingPhotographers = collect();
        if ($project->status === 'published') {
            $matchingPhotographers = $this->getTopMatchingPhotographers($project, $matchingService, 6);
        }

        return view('client.projects.show', compact('project', 'matchingPhotographers'));
    }

    private function getTopMatchingPhotographers(PhotoProject $project, PhotographerMatchingService $matchingService, int $limit = 6)
    {
        $query = Photographer::query()
                             ->whereHas('user')
                             ->with(['user', 'specialties']);

        $query->verified();

        $photographers = $query->get();

        $scoredPhotographers = $photographers->map(function ($photographer) use ($project, $matchingService) {
            $photographer->matching_score = $matchingService->calculateScore($photographer, $project);

            return $photographer;
        });

        return $scoredPhotographers
            ->sortByDesc(fn($p) => $p->matching_score['total'])
            ->take($limit)
            ->values();
    }

    public function edit(PhotoProject $project): View
    {
        $this->authorize('update', $project);

        if (!in_array($project->status, ['draft', 'published'])) {
            return redirect()
                ->route('client.projects.show', $project)
                ->with('error', 'Ce projet ne peut plus être modifié.');
        }

        $specialties = Specialty::all();
        $projectTypes = ProjectType::options();

        return view('client.projects.edit', compact('project', 'specialties', 'projectTypes'));
    }

    public function update(UpdatePhotoProjectRequest $request, PhotoProject $project): RedirectResponse
    {
        $this->authorize('update', $project);

        if (!in_array($project->status, ['draft', 'published'])) {
            return redirect()
                ->route('client.projects.show', $project)
                ->with('error', 'Ce projet ne peut plus être modifié.');
        }

        $project->update($request->validated());

        return redirect()
            ->route('client.projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(PhotoProject $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $hasAcceptedRequests = $project->bookingRequests()
                                       ->where('status', 'accepted')
                                       ->exists();

        if ($hasAcceptedRequests) {
            return redirect()
                ->route('client.projects.show', $project)
                ->with('error', 'Impossible de supprimer ce projet car il a des demandes acceptées.');
        }

        $project->delete();

        return redirect()
            ->route('client.projects.index')
            ->with('success', 'Projet supprimé avec succès.');
    }
}
