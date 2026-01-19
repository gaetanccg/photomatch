<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPhotographerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Photographer::query()
            ->verified()
            ->whereHas('user')
            ->with(['user', 'specialties']);

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->withSpecialty($request->specialty);
        }

        // Filter by price range
        if ($request->filled('min_rate') || $request->filled('max_rate')) {
            $query->inPriceRange($request->min_rate, $request->max_rate);
        }

        // Filter by location (simple text search)
        if ($request->filled('location')) {
            $query->nearLocation($request->location);
        }

        $photographers = $query->paginate(12)->withQueryString();
        $specialties = Specialty::all();

        return view('photographers.index', compact('photographers', 'specialties'));
    }

    public function show(Photographer $photographer): View
    {
        $photographer->load(['user', 'specialties']);

        // Get availabilities for next 30 days
        $availabilities = $photographer->availabilities()
            ->whereBetween('date', [Carbon::today(), Carbon::today()->addDays(30)])
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($item) => $item->date->format('Y-m-d'));

        // Get client's projects if authenticated and is a client
        $clientProjects = null;
        if (auth()->check() && auth()->user()->isClient()) {
            $clientProjects = auth()->user()->photoProjects()
                ->where('status', 'published')
                ->get();
        }

        return view('photographers.show', compact('photographer', 'availabilities', 'clientProjects'));
    }
}
