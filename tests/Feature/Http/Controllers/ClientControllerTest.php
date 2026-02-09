<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\PhotoProject;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->client()->create();
    }

    public function test_dashboard_displays_stats(): void
    {
        PhotoProject::factory()->forClient($this->client)->published()->count(3)->create();
        PhotoProject::factory()->forClient($this->client)->draft()->count(2)->create();

        $project = PhotoProject::factory()->forClient($this->client)->published()->create();
        BookingRequest::factory()->forProject($project)->pending()->count(2)->create();
        BookingRequest::factory()->forProject($project)->accepted()->count(1)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertViewHas('stats');
        $response->assertViewHas('recentProjects');
        $response->assertViewHas('recentRequests');

        $stats = $response->viewData('stats');
        $this->assertEquals(6, $stats['total_projects']);
        $this->assertEquals(4, $stats['published_projects']);
        $this->assertEquals(2, $stats['pending_requests']);
        $this->assertEquals(1, $stats['accepted_requests']);
    }

    public function test_requests_lists_all_booking_requests(): void
    {
        $project = PhotoProject::factory()->forClient($this->client)->create();
        BookingRequest::factory()->forProject($project)->count(5)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.index'));

        $response->assertOk();
        $response->assertViewHas('requests');
        $this->assertCount(5, $response->viewData('requests'));
    }

    public function test_show_request_displays_booking_details(): void
    {
        $project = PhotoProject::factory()->forClient($this->client)->create();
        $bookingRequest = BookingRequest::factory()->forProject($project)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show', $bookingRequest));

        $response->assertOk();
        $response->assertViewHas('bookingRequest');
    }

    public function test_show_request_forbids_other_clients(): void
    {
        $otherClient = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($otherClient)->create();
        $bookingRequest = BookingRequest::factory()->forProject($project)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show', $bookingRequest));

        $response->assertForbidden();
    }

    public function test_history_displays_completed_missions(): void
    {
        $project = PhotoProject::factory()->forClient($this->client)->create();
        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create();
        BookingRequest::factory()->forProject($project)->pending()->count(2)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.history.index'));

        $response->assertOk();
        $response->assertViewHas('missions');
        $response->assertViewHas('totalMissions');
        $response->assertViewHas('totalSpent');
        $response->assertViewHas('reviewsGiven');
        $response->assertViewHas('years');

        $this->assertCount(3, $response->viewData('missions'));
    }

    public function test_history_can_filter_by_year(): void
    {
        $project = PhotoProject::factory()->forClient($this->client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now()->subYear(),
        ]);
        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now(),
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.history.index', ['year' => now()->year]));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('missions'));
    }

    public function test_history_calculates_total_spent(): void
    {
        $project = PhotoProject::factory()->forClient($this->client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create([
            'proposed_price' => 500.00,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.history.index'));

        $this->assertEquals(1500.00, $response->viewData('totalSpent'));
    }

    public function test_history_counts_reviews_given(): void
    {
        Review::factory()->count(3)->create(['client_id' => $this->client->id]);

        $response = $this->actingAs($this->client)
            ->get(route('client.history.index'));

        $this->assertEquals(3, $response->viewData('reviewsGiven'));
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('client.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
