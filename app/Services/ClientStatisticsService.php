<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use App\Queries\ClientBookingRequestsQuery;
use Illuminate\Support\Facades\Cache;

class ClientStatisticsService
{
    public function getDashboardStats(User $client): array
    {
        return Cache::remember("client_stats:{$client->id}", 300, function () use ($client) {
            $query = new ClientBookingRequestsQuery($client);

            return [
                'total_projects' => $client->photoProjects()->count(),
                'published_projects' => $client->photoProjects()->where('status', 'published')->count(),
                'pending_requests' => $query->pending()->count(),
                'accepted_requests' => $query->accepted()->count(),
            ];
        });
    }

    public function getHistoryStats(User $client): array
    {
        $query = new ClientBookingRequestsQuery($client);

        return [
            'total_missions' => $query->accepted()->count(),
            'total_spent' => $query->accepted()
                ->whereNotNull('proposed_price')
                ->sum('proposed_price'),
            'reviews_given' => Review::where('client_id', $client->id)->count(),
        ];
    }

    public function clearCache(User $client): void
    {
        Cache::forget("client_stats:{$client->id}");
    }
}
