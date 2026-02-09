<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\PhotoProject;
use App\Models\Photographer;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_verified_photographers(): void
    {
        Photographer::factory()->verified()->count(5)->create();
        Photographer::factory()->unverified()->count(3)->create();

        $response = $this->get(route('search.index'));

        $response->assertOk();
        $response->assertViewHas('photographers');
        $this->assertCount(5, $response->viewData('photographers'));
    }

    public function test_index_filters_by_specialty(): void
    {
        $specialty = Specialty::factory()->create();

        $matchingPhotographer = Photographer::factory()->verified()->create();
        $matchingPhotographer->specialties()->attach($specialty);

        Photographer::factory()->verified()->count(3)->create();

        $response = $this->get(route('search.index', [
            'specialty_ids' => [$specialty->id],
        ]));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('photographers'));
    }

    public function test_index_filters_by_location(): void
    {
        Photographer::factory()->verified()->create(['location' => 'Paris, France']);
        Photographer::factory()->verified()->create(['location' => 'Lyon, France']);
        Photographer::factory()->verified()->create(['location' => 'Marseille, France']);

        $response = $this->get(route('search.index', ['location' => 'Paris']));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('photographers'));
    }

    public function test_index_filters_by_budget(): void
    {
        Photographer::factory()->verified()->create(['hourly_rate' => 50]);
        Photographer::factory()->verified()->create(['hourly_rate' => 100]);
        Photographer::factory()->verified()->create(['hourly_rate' => 150]);

        $response = $this->get(route('search.index', [
            'min_budget' => 75,
            'max_budget' => 125,
        ]));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('photographers'));
    }

    public function test_index_filters_by_rating(): void
    {
        Photographer::factory()->verified()->create(['rating' => 3.5]);
        Photographer::factory()->verified()->create(['rating' => 4.2]);
        Photographer::factory()->verified()->create(['rating' => 4.8]);

        $response = $this->get(route('search.index', ['min_rating' => 4]));

        $response->assertOk();
        $this->assertCount(2, $response->viewData('photographers'));
    }

    public function test_index_sorts_by_rating_desc_by_default(): void
    {
        Photographer::factory()->verified()->create(['rating' => 3.0]);
        Photographer::factory()->verified()->create(['rating' => 5.0]);
        Photographer::factory()->verified()->create(['rating' => 4.0]);

        $response = $this->get(route('search.index'));

        $photographers = $response->viewData('photographers');
        $this->assertEquals(5.0, $photographers->first()->getRawOriginal('rating'));
    }

    public function test_index_sorts_by_custom_column(): void
    {
        Photographer::factory()->verified()->create(['hourly_rate' => 150]);
        Photographer::factory()->verified()->create(['hourly_rate' => 50]);
        Photographer::factory()->verified()->create(['hourly_rate' => 100]);

        $response = $this->get(route('search.index', [
            'sort' => 'hourly_rate',
            'dir' => 'asc',
        ]));

        $photographers = $response->viewData('photographers');
        $this->assertEquals(50, $photographers->first()->getRawOriginal('hourly_rate'));
    }

    public function test_index_returns_map_data(): void
    {
        Photographer::factory()->verified()->count(3)->create([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        $response = $this->get(route('search.index'));

        $response->assertOk();
        $response->assertViewHas('mapPhotographers');
        $this->assertCount(3, $response->viewData('mapPhotographers'));
    }

    public function test_index_with_project_uses_matching_service(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();

        Photographer::factory()->verified()->count(3)->create();

        $response = $this->actingAs($client)
            ->get(route('search.index', ['project_id' => $project->id]));

        $response->assertOk();
        $response->assertViewHas('project');
        $response->assertViewHas('useMatching', true);
    }

    public function test_index_with_project_forbids_non_owners(): void
    {
        $owner = User::factory()->client()->create();
        $otherUser = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($owner)->published()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('search.index', ['project_id' => $project->id]));

        $response->assertForbidden();
    }

    public function test_index_validates_sort_parameter(): void
    {
        Photographer::factory()->verified()->count(3)->create();

        $response = $this->get(route('search.index', ['sort' => 'invalid_column']));

        $response->assertOk();
        $this->assertEquals('rating', $response->viewData('sortBy'));
    }

    public function test_index_paginates_results(): void
    {
        Photographer::factory()->verified()->count(25)->create();

        $response = $this->get(route('search.index'));

        $response->assertOk();
        $photographers = $response->viewData('photographers');
        $this->assertEquals(12, $photographers->count());
        $this->assertEquals(25, $photographers->total());
    }

    public function test_index_returns_specialties(): void
    {
        Specialty::factory()->count(5)->create();

        $response = $this->get(route('search.index'));

        $response->assertOk();
        $response->assertViewHas('specialties');
    }
}
