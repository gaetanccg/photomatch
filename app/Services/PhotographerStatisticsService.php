<?php

namespace App\Services;

use App\Models\Photographer;
use Illuminate\Support\Facades\DB;

class PhotographerStatisticsService
{
    public function getHistoryStats(Photographer $photographer): array
    {
        return [
            'total_missions' => $photographer->bookingRequests()->where('status', 'accepted')->count(),
            'total_earnings' => $photographer->bookingRequests()
                ->where('status', 'accepted')
                ->whereNotNull('proposed_price')
                ->sum('proposed_price'),
            'avg_rating' => $photographer->reviews()->avg('rating'),
            'review_count' => $photographer->reviews()->count(),
        ];
    }

    public function getReviewStats(Photographer $photographer): array
    {
        return [
            'total_reviews' => $photographer->reviews()->count(),
            'average_rating' => $photographer->reviews()->avg('rating'),
            'five_star' => $photographer->reviews()->where('rating', 5)->count(),
            'pending_responses' => $photographer->reviews()->whereNull('photographer_response')->count(),
        ];
    }

    public function getAvailableYears(Photographer $photographer): \Illuminate\Support\Collection
    {
        $driver = DB::connection()->getDriverName();

        $yearExpression = match ($driver) {
            'sqlite' => "strftime('%Y', responded_at) as year",
            default => 'EXTRACT(YEAR FROM responded_at) as year',
        };

        return $photographer->bookingRequests()
            ->where('status', 'accepted')
            ->whereNotNull('responded_at')
            ->selectRaw($yearExpression)
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year);
    }
}
