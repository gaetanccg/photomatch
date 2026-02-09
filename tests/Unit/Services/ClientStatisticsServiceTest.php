<?php

namespace Tests\Unit\Services;

use App\Models\BookingRequest;
use App\Models\PhotoProject;
use App\Models\Review;
use App\Models\User;
use App\Services\ClientStatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ClientStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ClientStatisticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ClientStatisticsService();
    }

    public function test_get_dashboard_stats_returns_correct_data(): void
    {
        $client = User::factory()->client()->create();

        PhotoProject::factory()->forClient($client)->published()->count(3)->create();
        PhotoProject::factory()->forClient($client)->draft()->count(2)->create();

        $publishedProject = PhotoProject::factory()->forClient($client)->published()->create();
        BookingRequest::factory()->forProject($publishedProject)->pending()->count(2)->create();
        BookingRequest::factory()->forProject($publishedProject)->accepted()->count(1)->create();

        $stats = $this->service->getDashboardStats($client);

        $this->assertEquals(6, $stats['total_projects']);
        $this->assertEquals(4, $stats['published_projects']);
        $this->assertEquals(2, $stats['pending_requests']);
        $this->assertEquals(1, $stats['accepted_requests']);
    }

    public function test_get_dashboard_stats_caches_results(): void
    {
        $client = User::factory()->client()->create();

        $this->service->getDashboardStats($client);

        $this->assertTrue(Cache::has("client_stats:{$client->id}"));
    }

    public function test_get_history_stats_returns_correct_data(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()
            ->forProject($project)
            ->accepted()
            ->count(3)
            ->create(['proposed_price' => 500.00]);

        Review::factory()->count(2)->create(['client_id' => $client->id]);

        $stats = $this->service->getHistoryStats($client);

        $this->assertEquals(3, $stats['total_missions']);
        $this->assertEquals(1500.00, $stats['total_spent']);
        $this->assertEquals(2, $stats['reviews_given']);
    }

    public function test_clear_cache_removes_cached_stats(): void
    {
        $client = User::factory()->client()->create();

        $this->service->getDashboardStats($client);
        $this->assertTrue(Cache::has("client_stats:{$client->id}"));

        $this->service->clearCache($client);

        $this->assertFalse(Cache::has("client_stats:{$client->id}"));
    }

    public function test_get_history_stats_handles_null_prices(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();

        BookingRequest::factory()
            ->forProject($project)
            ->accepted()
            ->create(['proposed_price' => null]);

        $stats = $this->service->getHistoryStats($client);

        $this->assertEquals(1, $stats['total_missions']);
        $this->assertEquals(0, $stats['total_spent']);
    }
}
