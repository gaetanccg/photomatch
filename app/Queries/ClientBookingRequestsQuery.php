<?php

namespace App\Queries;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClientBookingRequestsQuery
{
    public function __construct(
        private User $client
    ) {}

    public function base(): Builder
    {
        return BookingRequest::whereHas('project', fn ($q) => $q->where('client_id', $this->client->id));
    }

    public function withRelations(array $relations = ['photographer.user', 'project']): Builder
    {
        return $this->base()->with($relations);
    }

    public function pending(): Builder
    {
        return $this->base()->where('status', 'pending');
    }

    public function accepted(): Builder
    {
        return $this->base()->where('status', 'accepted');
    }

    public function completed(): Builder
    {
        return $this->accepted();
    }

    public function forYear(?int $year): Builder
    {
        $query = $this->accepted();

        if ($year) {
            $query->whereYear('responded_at', $year);
        }

        return $query;
    }

    public function availableYears(): \Illuminate\Support\Collection
    {
        $driver = DB::connection()->getDriverName();

        $yearExpression = $driver === 'sqlite'
            ? "strftime('%Y', responded_at) as year"
            : 'YEAR(responded_at) as year';

        return $this->accepted()
            ->whereNotNull('responded_at')
            ->selectRaw($yearExpression)
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year);
    }
}
