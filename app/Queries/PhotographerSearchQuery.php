<?php

namespace App\Queries;

use App\Models\Photographer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PhotographerSearchQuery
{
    private Builder $query;
    private array $filters;
    private string $sortBy = 'rating';
    private string $sortDir = 'desc';

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->query = Photographer::query()
            ->verified()
            ->whereHas('user');
    }

    public function applyFilters(): self
    {
        $this->applySpecialtyFilter()
            ->applyLocationFilter()
            ->applyBudgetFilter()
            ->applyAvailabilityFilter()
            ->applyRatingFilter();

        return $this;
    }

    public function withRelations(): self
    {
        $this->query->with([
            'user',
            'specialties',
            'reviews',
            'portfolioImages' => fn($q) => $q->featured()->limit(1)
        ])->withCount('reviews');

        return $this;
    }

    public function sortBy(string $column, string $direction = 'desc'): self
    {
        $allowedSorts = ['rating', 'hourly_rate', 'experience_years', 'total_missions'];

        $this->sortBy = in_array($column, $allowedSorts) ? $column : 'rating';
        $this->sortDir = $direction === 'asc' ? 'asc' : 'desc';

        return $this;
    }

    public function paginate(int $perPage = 12): LengthAwarePaginator
    {
        return $this->query
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function get(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();
    }

    private function applySpecialtyFilter(): self
    {
        if ($ids = $this->filters['specialty_ids'] ?? null) {
            $ids = is_array($ids) ? $ids : [$ids];
            $this->query->whereHas('specialties', fn($sq) => $sq->whereIn('specialties.id', $ids));
        }

        return $this;
    }

    private function applyLocationFilter(): self
    {
        if ($location = $this->filters['location'] ?? null) {
            $this->query->nearLocation($location);
        }

        return $this;
    }

    private function applyBudgetFilter(): self
    {
        if ($max = $this->filters['max_budget'] ?? null) {
            $this->query->where('hourly_rate', '<=', $max);
        }

        if ($min = $this->filters['min_budget'] ?? null) {
            $this->query->where('hourly_rate', '>=', $min);
        }

        return $this;
    }

    private function applyAvailabilityFilter(): self
    {
        if ($date = $this->filters['available_date'] ?? null) {
            $this->query->availableOn($date);
        }

        return $this;
    }

    private function applyRatingFilter(): self
    {
        if ($rating = $this->filters['min_rating'] ?? null) {
            $this->query->minRating($rating);
        }

        return $this;
    }
}
