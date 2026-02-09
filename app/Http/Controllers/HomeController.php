<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use App\Models\Specialty;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Top rated photographers (cached for 1 hour)
        $topPhotographers = Cache::remember('home.top_photographers', 3600, function () {
            return Photographer::with(['user', 'specialties'])
                ->whereNotNull('rating')
                ->where('rating', '>', 0)
                ->orderByDesc('rating')
                ->take(6)
                ->get();
        });

        // If not enough rated photographers, get recent ones
        if ($topPhotographers->count() < 6) {
            $additionalPhotographers = Photographer::with(['user', 'specialties'])
                ->whereNotIn('id', $topPhotographers->pluck('id'))
                ->latest()
                ->take(6 - $topPhotographers->count())
                ->get();

            $topPhotographers = $topPhotographers->merge($additionalPhotographers);
        }

        // Popular specialties (cached for 1 hour)
        $popularSpecialties = Cache::remember('home.popular_specialties', 3600, function () {
            return Specialty::withCount('photographers')
                ->orderByDesc('photographers_count')
                ->take(4)
                ->get();
        });

        // Stats (cached for 1 hour)
        $stats = Cache::remember('home.stats', 3600, function () {
            return [
                'photographers_count' => Photographer::count(),
                'specialties_count' => Specialty::count(),
            ];
        });

        // Schema.org structured data for homepage
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => config('seo.site_url').'/#website',
                    'url' => config('seo.site_url'),
                    'name' => config('seo.site_name'),
                    'description' => config('seo.default.description'),
                    'inLanguage' => 'fr-FR',
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => config('seo.site_url').'/search-photographers?location={search_term}',
                        ],
                        'query-input' => 'required name=search_term',
                    ],
                ],
                [
                    '@type' => 'Organization',
                    '@id' => config('seo.site_url').'/#organization',
                    'name' => config('seo.site_name'),
                    'url' => config('seo.site_url'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => config('seo.site_url').'/images/logo.png',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'customer service',
                        'email' => config('seo.legal.email'),
                        'availableLanguage' => 'French',
                    ],
                    'sameAs' => config('seo.schema.organization.sameAs', []),
                ],
            ],
        ];

        return view('welcome', compact('topPhotographers', 'popularSpecialties', 'stats', 'schema'));
    }
}
