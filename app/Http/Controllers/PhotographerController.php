<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePhotographerProfileRequest;
use App\Http\Requests\UpdatePhotographerSpecialtiesRequest;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PhotographerController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $photographer = $user->photographer()->with(['specialties', 'bookingRequests.project.client', 'availabilities'])->firstOrFail();

        $pendingRequestsCount = $photographer->bookingRequests()->pending()->count();

        $acceptedThisMonth = $photographer->bookingRequests()
            ->accepted()
            ->whereMonth('responded_at', Carbon::now()->month)
            ->whereYear('responded_at', Carbon::now()->year)
            ->count();

        $latestRequests = $photographer->bookingRequests()
            ->with(['project.client'])
            ->latest('sent_at')
            ->take(5)
            ->get();

        // Disponibilités pour les 28 prochains jours
        $today = Carbon::today();
        $calendarDays = collect();

        // Index availabilities by date string for faster lookup
        $availabilitiesIndexed = $photographer->availabilities->keyBy(fn ($a) => $a->date->format('Y-m-d'));

        for ($i = 0; $i < 28; $i++) {
            $date = $today->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            $calendarDays->push([
                'date' => $date,
                'availability' => $availabilitiesIndexed->get($dateKey),
            ]);
        }

        return view('photographer.dashboard', compact(
            'photographer',
            'pendingRequestsCount',
            'acceptedThisMonth',
            'latestRequests',
            'calendarDays'
        ));
    }

    public function edit(): View
    {
        $user = auth()->user();
        $photographer = $user->photographer()->with('specialties')->firstOrFail();
        $specialties = Specialty::all();

        return view('photographer.profile.edit', compact('photographer', 'specialties'));
    }

    public function update(UpdatePhotographerProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $photographer = $user->photographer;

        $photographer->update($request->validated());

        if ($request->filled('phone')) {
            $user->update(['phone' => $request->phone]);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updateSpecialties(UpdatePhotographerSpecialtiesRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $photographer = $user->photographer;

        $syncData = [];
        foreach ($request->specialties as $specialty) {
            $syncData[$specialty['id']] = ['experience_level' => $specialty['level']];
        }

        $photographer->specialties()->sync($syncData);

        return back()->with('success', 'Spécialités mises à jour avec succès.');
    }
}
