<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ProjectType;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotoProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->client()->create();
    }

    public function test_index_lists_client_projects(): void
    {
        PhotoProject::factory()->forClient($this->client)->count(5)->create();

        $otherClient = User::factory()->client()->create();
        PhotoProject::factory()->forClient($otherClient)->count(3)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.projects.index'));

        $response->assertOk();
        $response->assertViewHas('projects');
        $this->assertCount(5, $response->viewData('projects'));
    }

    public function test_create_displays_form(): void
    {
        Specialty::factory()->count(5)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.projects.create'));

        $response->assertOk();
        $response->assertViewHas('specialties');
        $response->assertViewHas('projectTypes');
    }

    public function test_store_creates_project(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('client.projects.store'), [
                'title' => 'Mariage de Test',
                'description' => 'Description du projet de test',
                'project_type' => ProjectType::Event->value,
                'event_date' => now()->addMonth()->format('Y-m-d'),
                'location' => 'Paris, France',
                'budget_min' => 500,
                'budget_max' => 1500,
                'status' => 'published',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('photo_projects', [
            'client_id' => $this->client->id,
            'title' => 'Mariage de Test',
            'project_type' => ProjectType::Event->value,
        ]);
    }

    public function test_show_displays_project_with_matching_photographers(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->published()
            ->create();

        Photographer::factory()->verified()->count(3)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.projects.show', $project));

        $response->assertOk();
        $response->assertViewHas('project');
        $response->assertViewHas('matchingPhotographers');
    }

    public function test_show_forbids_non_owners(): void
    {
        $otherClient = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($otherClient)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.projects.show', $project));

        $response->assertForbidden();
    }

    public function test_edit_displays_form(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->draft()
            ->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.projects.edit', $project));

        $response->assertOk();
        $response->assertViewHas('project');
        $response->assertViewHas('projectTypes');
    }

    public function test_update_modifies_project(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->draft()
            ->create();

        $response = $this->actingAs($this->client)
            ->put(route('client.projects.update', $project), [
                'title' => 'Updated Title',
                'description' => 'Updated description',
                'project_type' => ProjectType::Portrait->value,
                'location' => 'Lyon, France',
                'budget_min' => 600,
                'budget_max' => 1800,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $project->refresh();
        $this->assertEquals('Updated Title', $project->title);
        $this->assertEquals(ProjectType::Portrait, $project->project_type);
    }

    public function test_destroy_deletes_project(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->create();

        $response = $this->actingAs($this->client)
            ->delete(route('client.projects.destroy', $project));

        $response->assertRedirect(route('client.projects.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('photo_projects', ['id' => $project->id]);
    }

    public function test_destroy_forbids_deletion_with_accepted_requests(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->create();

        BookingRequest::factory()
            ->forProject($project)
            ->accepted()
            ->create();

        $response = $this->actingAs($this->client)
            ->delete(route('client.projects.destroy', $project));

        // Policy blocks deletion, returning 403
        $response->assertForbidden();

        $this->assertDatabaseHas('photo_projects', ['id' => $project->id]);
    }

    public function test_project_types_use_enum(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.projects.create'));

        $projectTypes = $response->viewData('projectTypes');

        $this->assertArrayHasKey('event', $projectTypes);
        $this->assertArrayHasKey('product', $projectTypes);
        $this->assertArrayHasKey('real_estate', $projectTypes);
        $this->assertArrayHasKey('corporate', $projectTypes);
        $this->assertArrayHasKey('portrait', $projectTypes);
        $this->assertArrayHasKey('other', $projectTypes);
    }

    public function test_guest_cannot_access_projects(): void
    {
        $response = $this->get(route('client.projects.index'));

        $response->assertRedirect(route('login'));
    }
}
