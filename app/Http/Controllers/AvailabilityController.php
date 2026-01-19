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

        $availabilities = $photographer->availabilities()
            ->whereBetween('date', [Carbon::today(), Carbon::today()->addDays(60)])
            ->orderBy('date')
            ->get()
            ->groupBy(fn ($item) => $item->date->format('Y-m'));

        return view('photographer.availabilities.index', compact('availabilities', 'photographer'));
    }

    public function store(StoreAvailabilityRequest $request): RedirectResponse
    {
        $photographer = auth()->user()->photographer;

        $exists = $photographer->availabilities()
            ->whereDate('date', $request->date)
            ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Une disponibilité existe déjà pour cette date.']);
        }

        $photographer->availabilities()->create($request->validated());

        return back()->with('success', 'Disponibilité ajoutée avec succès.');
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
