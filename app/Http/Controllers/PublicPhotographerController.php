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
        $photographer->load(['user', 'specialties', 'reviews.client', 'portfolioImages']);

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

        // Schema.org structured data for photographer profile
        $schema = $this->buildPhotographerSchema($photographer);

        return view('photographers.show', compact('photographer', 'availabilities', 'clientProjects', 'schema'));
    }

    private function buildPhotographerSchema(Photographer $photographer): array
    {
        $siteUrl = config('seo.site_url');
        $profileUrl = route('photographers.show', $photographer);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => $profileUrl,
            'name' => $photographer->user->name,
            'description' => $photographer->bio ?? "Photographe professionnel",
            'url' => $profileUrl,
            'image' => $photographer->portfolioImages->first()?->url ?? $photographer->user->profile_photo_url,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $photographer->location,
                'addressCountry' => 'FR',
            ],
            'priceRange' => $photographer->hourly_rate ? $photographer->hourly_rate . '€/h' : null,
            'additionalType' => 'https://schema.org/Photographer',
        ];

        // Add aggregate rating if reviews exist
        if ($photographer->reviews_count > 0 && $photographer->rating) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format($photographer->rating, 1),
                'reviewCount' => $photographer->reviews_count,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        // Add individual reviews
        if ($photographer->reviews->count() > 0) {
            $schema['review'] = $photographer->reviews->take(5)->map(fn($review) => [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $review->rating,
                    'bestRating' => '5',
                    'worstRating' => '1',
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $review->client->name ?? 'Client',
                ],
                'reviewBody' => $review->comment,
                'datePublished' => $review->created_at->toIso8601String(),
            ])->values()->toArray();
        }

        // Add services offered (specialties)
        if ($photographer->specialties->count() > 0) {
            $schema['makesOffer'] = $photographer->specialties->map(fn($specialty) => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => $specialty->name,
                    'description' => "Service de photographie : {$specialty->name}",
                ],
            ])->values()->toArray();
        }

        return $schema;
    }
}
