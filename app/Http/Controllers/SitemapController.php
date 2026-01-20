<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use App\Models\Specialty;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap', 3600, function () {
            return $this->generateSitemap();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml');
    }

    private function generateSitemap(): string
    {
        $siteUrl = config('seo.site_url');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/search-photographers', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/photographers', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => '/login', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/register', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => '/mentions-legales', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/conditions-generales', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/politique-confidentialite', 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => '/cookies', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= $this->buildUrlElement(
                $siteUrl . $page['url'],
                now()->toIso8601String(),
                $page['changefreq'],
                $page['priority']
            );
        }

        // Photographer profiles
        $photographers = Photographer::verified()
            ->whereHas('user')
            ->with('user')
            ->get();

        foreach ($photographers as $photographer) {
            $xml .= $this->buildUrlElement(
                route('photographers.show', $photographer),
                $photographer->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                'weekly',
                '0.7'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function buildUrlElement(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return sprintf(
            '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%s</priority></url>',
            htmlspecialchars($loc),
            $lastmod,
            $changefreq,
            $priority
        );
    }
}
