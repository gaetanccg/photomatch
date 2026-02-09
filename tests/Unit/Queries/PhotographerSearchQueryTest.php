<?php

namespace Tests\Unit\Queries;

use App\Models\Photographer;
use App\Models\Specialty;
use App\Queries\PhotographerSearchQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerSearchQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_verified_photographers(): void
    {
        Photographer::factory()->verified()->count(3)->create();
        Photographer::factory()->unverified()->count(2)->create();

        $query = new PhotographerSearchQuery();
        $results = $query->applyFilters()->get();

        $this->assertCount(3, $results);
    }

    public function test_it_filters_by_specialty(): void
    {
        $specialty = Specialty::factory()->create();

        $photographer1 = Photographer::factory()->verified()->create();
        $photographer1->specialties()->attach($specialty);

        $photographer2 = Photographer::factory()->verified()->create();

        $query = new PhotographerSearchQuery(['specialty_ids' => [$specialty->id]]);
        $results = $query->applyFilters()->get();

        $this->assertCount(1, $results);
        $this->assertEquals($photographer1->id, $results->first()->id);
    }

    public function test_it_filters_by_location(): void
    {
        Photographer::factory()->verified()->create(['location' => 'Paris, France']);
        Photographer::factory()->verified()->create(['location' => 'Lyon, France']);

        $query = new PhotographerSearchQuery(['location' => 'Paris']);
        $results = $query->applyFilters()->get();

        $this->assertCount(1, $results);
    }

    public function test_it_filters_by_max_budget(): void
    {
        Photographer::factory()->verified()->create(['hourly_rate' => 50]);
        Photographer::factory()->verified()->create(['hourly_rate' => 100]);
        Photographer::factory()->verified()->create(['hourly_rate' => 150]);

        $query = new PhotographerSearchQuery(['max_budget' => 100]);
        $results = $query->applyFilters()->get();

        $this->assertCount(2, $results);
    }

    public function test_it_filters_by_min_budget(): void
    {
        Photographer::factory()->verified()->create(['hourly_rate' => 50]);
        Photographer::factory()->verified()->create(['hourly_rate' => 100]);
        Photographer::factory()->verified()->create(['hourly_rate' => 150]);

        $query = new PhotographerSearchQuery(['min_budget' => 100]);
        $results = $query->applyFilters()->get();

        $this->assertCount(2, $results);
    }

    public function test_it_filters_by_min_rating(): void
    {
        Photographer::factory()->verified()->create(['rating' => 3.5]);
        Photographer::factory()->verified()->create(['rating' => 4.2]);
        Photographer::factory()->verified()->create(['rating' => 4.8]);

        $query = new PhotographerSearchQuery(['min_rating' => 4.0]);
        $results = $query->applyFilters()->get();

        $this->assertCount(2, $results);
    }

    public function test_it_sorts_by_rating_desc_by_default(): void
    {
        Photographer::factory()->verified()->create(['rating' => 3.0]);
        Photographer::factory()->verified()->create(['rating' => 5.0]);
        Photographer::factory()->verified()->create(['rating' => 4.0]);

        $query = new PhotographerSearchQuery();
        $results = $query->applyFilters()->get();

        $this->assertEquals(5.0, $results->first()->getRawOriginal('rating'));
    }

    public function test_it_sorts_by_custom_column(): void
    {
        Photographer::factory()->verified()->create(['hourly_rate' => 150]);
        Photographer::factory()->verified()->create(['hourly_rate' => 50]);
        Photographer::factory()->verified()->create(['hourly_rate' => 100]);

        $query = new PhotographerSearchQuery();
        $results = $query->applyFilters()->sortBy('hourly_rate', 'asc')->get();

        $this->assertEquals(50, $results->first()->getRawOriginal('hourly_rate'));
    }

    public function test_it_validates_sort_column(): void
    {
        Photographer::factory()->verified()->count(3)->create();

        $query = new PhotographerSearchQuery();
        $results = $query->applyFilters()->sortBy('invalid_column', 'desc')->get();

        $this->assertCount(3, $results);
    }

    public function test_it_paginates_results(): void
    {
        Photographer::factory()->verified()->count(25)->create();

        $query = new PhotographerSearchQuery();
        $results = $query->applyFilters()->paginate(10);

        $this->assertEquals(10, $results->count());
        $this->assertEquals(25, $results->total());
    }

    public function test_with_relations_eager_loads(): void
    {
        Photographer::factory()->verified()->create();

        $query = new PhotographerSearchQuery();
        $result = $query->applyFilters()->withRelations()->get()->first();

        $this->assertTrue($result->relationLoaded('user'));
        $this->assertTrue($result->relationLoaded('specialties'));
        $this->assertTrue($result->relationLoaded('reviews'));
    }

    public function test_it_combines_multiple_filters(): void
    {
        $specialty = Specialty::factory()->create();

        $match = Photographer::factory()->verified()->create([
            'location' => 'Paris, France',
            'hourly_rate' => 80,
            'rating' => 4.5,
        ]);
        $match->specialties()->attach($specialty);

        $noMatch = Photographer::factory()->verified()->create([
            'location' => 'Lyon, France',
            'hourly_rate' => 150,
            'rating' => 3.0,
        ]);

        $query = new PhotographerSearchQuery([
            'specialty_ids' => [$specialty->id],
            'location' => 'Paris',
            'max_budget' => 100,
            'min_rating' => 4.0,
        ]);

        $results = $query->applyFilters()->get();

        $this->assertCount(1, $results);
        $this->assertEquals($match->id, $results->first()->id);
    }
}
