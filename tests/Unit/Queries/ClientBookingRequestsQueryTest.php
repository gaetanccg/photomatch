<?php

namespace Tests\Unit\Queries;

use App\Models\BookingRequest;
use App\Models\PhotoProject;
use App\Models\User;
use App\Queries\ClientBookingRequestsQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientBookingRequestsQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_base_returns_only_client_requests(): void
    {
        $client1 = User::factory()->client()->create();
        $client2 = User::factory()->client()->create();

        $project1 = PhotoProject::factory()->forClient($client1)->create();
        $project2 = PhotoProject::factory()->forClient($client2)->create();

        BookingRequest::factory()->forProject($project1)->count(3)->create();
        BookingRequest::factory()->forProject($project2)->count(2)->create();

        $query = new ClientBookingRequestsQuery($client1);

        $this->assertEquals(3, $query->base()->count());
    }

    public function test_pending_filters_pending_requests(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->pending()->count(2)->create();
        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create();

        $query = new ClientBookingRequestsQuery($client);

        $this->assertEquals(2, $query->pending()->count());
    }

    public function test_accepted_filters_accepted_requests(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->pending()->count(2)->create();
        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create();

        $query = new ClientBookingRequestsQuery($client);

        $this->assertEquals(3, $query->accepted()->count());
    }

    public function test_for_year_filters_by_year(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now()->subYear(),
        ]);
        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now(),
        ]);

        $query = new ClientBookingRequestsQuery($client);

        $this->assertEquals(1, $query->forYear(now()->year)->count());
    }

    public function test_for_year_returns_all_when_null(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create();

        $query = new ClientBookingRequestsQuery($client);

        $this->assertEquals(3, $query->forYear(null)->count());
    }

    public function test_available_years_returns_distinct_years(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now()->subYears(2),
        ]);
        BookingRequest::factory()->forProject($project)->accepted()->create([
            'responded_at' => now()->subYear(),
        ]);
        BookingRequest::factory()->forProject($project)->accepted()->count(2)->create([
            'responded_at' => now(),
        ]);

        $query = new ClientBookingRequestsQuery($client);
        $years = $query->availableYears();

        $this->assertCount(3, $years);
    }

    public function test_with_relations_eager_loads_relations(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        BookingRequest::factory()->forProject($project)->create();

        $query = new ClientBookingRequestsQuery($client);
        $request = $query->withRelations()->first();

        $this->assertTrue($request->relationLoaded('photographer'));
        $this->assertTrue($request->relationLoaded('project'));
    }

    public function test_completed_is_alias_for_accepted(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()->forProject($project)->accepted()->count(3)->create();

        $query = new ClientBookingRequestsQuery($client);

        $this->assertEquals(
            $query->accepted()->count(),
            $query->completed()->count()
        );
    }
}
