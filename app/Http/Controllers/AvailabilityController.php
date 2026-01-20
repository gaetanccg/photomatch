<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAvailabilityRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Http\Requests\BulkUpdateAvailabilityRequest;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $photographer = auth()->user()->photographer;

        // Get availabilities for the next 90 days
        $availabilities = $photographer->availabilities()
            ->whereBetween('date', [Carbon::today(), Carbon::today()->addDays(90)])
            ->orderBy('date')
            ->get();

        // Prepare data for JavaScript calendar (keyed by date)
        $availabilitiesData = $availabilities->mapWithKeys(fn($a) => [
            $a->date->format('Y-m-d') => [
                'is_available' => $a->is_available,
                'note' => $a->note,
            ]
        ]);

        // Get accepted bookings to show on calendar
        $bookings = $photographer->bookingRequests()
            ->with(['project.client'])
            ->where('status', 'accepted')
            ->whereHas('project', fn($q) => $q->whereNotNull('event_date'))
            ->get();

        $bookingsData = $bookings
            ->filter(fn($b) => $b->project && $b->project->event_date)
            ->mapWithKeys(fn($b) => [
                $b->project->event_date->format('Y-m-d') => [
                    'client' => $b->project->client->name ?? 'Client',
                    'project' => $b->project->title,
                    'url' => route('photographer.requests.show', $b),
                ]
            ]);

        return view('photographer.availabilities.index', compact(
            'availabilities',
            'availabilitiesData',
            'bookingsData',
            'photographer'
        ));
    }

    public function store(StoreAvailabilityRequest $request): RedirectResponse
    {
        $photographer = auth()->user()->photographer;

        // Use updateOrCreate to allow updating existing availability
        $photographer->availabilities()->updateOrCreate(
            ['date' => $request->date],
            [
                'is_available' => $request->is_available,
                'note' => $request->note,
            ]
        );

        return back()->with('success', 'Disponibilité enregistrée.');
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability): RedirectResponse
    {
        Gate::authorize('update', $availability);

        $availability->update($request->validated());

        return back()->with('success', 'Disponibilité mise à jour avec succès.');
    }

    public function destroy(Availability $availability): RedirectResponse
    {
        Gate::authorize('delete', $availability);

        $availability->delete();

        return back()->with('success', 'Disponibilité supprimée avec succès.');
    }

    public function bulkUpdate(BulkUpdateAvailabilityRequest $request): RedirectResponse
    {
        $photographer = auth()->user()->photographer;

        foreach ($request->dates as $date) {
            $photographer->availabilities()->updateOrCreate(
                ['date' => $date],
                ['is_available' => $request->is_available, 'note' => $request->note]
            );
        }

        return back()->with('success', 'Disponibilités mises à jour avec succès.');
    }
}
