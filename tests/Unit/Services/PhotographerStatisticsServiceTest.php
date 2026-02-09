<?php

namespace Tests\Unit\Services;

use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\Review;
use App\Services\PhotographerStatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    private PhotographerStatisticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhotographerStatisticsService();
    }

    public function test_get_history_stats_returns_correct_data(): void
    {
        $photographer = Photographer::factory()->create();

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->count(5)
            ->create(['proposed_price' => 300.00]);

        Review::factory()->count(3)->create([
            'photographer_id' => $photographer->id,
            'rating' => 4,
        ]);

        $stats = $this->service->getHistoryStats($photographer);

        $this->assertEquals(5, $stats['total_missions']);
        $this->assertEquals(1500.00, $stats['total_earnings']);
        $this->assertEquals(4.0, $stats['avg_rating']);
        $this->assertEquals(3, $stats['review_count']);
    }

    public function test_get_review_stats_returns_correct_data(): void
    {
        $photographer = Photographer::factory()->create();

        Review::factory()->count(2)->create([
            'photographer_id' => $photographer->id,
            'rating' => 5,
            'photographer_response' => 'Merci!',
        ]);

        Review::factory()->count(3)->create([
            'photographer_id' => $photographer->id,
            'rating' => 4,
            'photographer_response' => null,
        ]);

        $stats = $this->service->getReviewStats($photographer);

        $this->assertEquals(5, $stats['total_reviews']);
        $this->assertEquals(4.4, $stats['average_rating']);
        $this->assertEquals(2, $stats['five_star']);
        $this->assertEquals(3, $stats['pending_responses']);
    }

    public function test_get_available_years_returns_distinct_years(): void
    {
        $photographer = Photographer::factory()->create();

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create(['responded_at' => now()->subYears(2)]);

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create(['responded_at' => now()->subYear()]);

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create(['responded_at' => now()]);

        $years = $this->service->getAvailableYears($photographer);

        $this->assertCount(3, $years);
        $this->assertTrue($years->contains(now()->year));
        $this->assertTrue($years->contains(now()->subYear()->year));
        $this->assertTrue($years->contains(now()->subYears(2)->year));
    }

    public function test_get_history_stats_handles_empty_data(): void
    {
        $photographer = Photographer::factory()->create();

        $stats = $this->service->getHistoryStats($photographer);

        $this->assertEquals(0, $stats['total_missions']);
        $this->assertEquals(0, $stats['total_earnings']);
        $this->assertNull($stats['avg_rating']);
        $this->assertEquals(0, $stats['review_count']);
    }

    public function test_get_review_stats_handles_empty_data(): void
    {
        $photographer = Photographer::factory()->create();

        $stats = $this->service->getReviewStats($photographer);

        $this->assertEquals(0, $stats['total_reviews']);
        $this->assertNull($stats['average_rating']);
        $this->assertEquals(0, $stats['five_star']);
        $this->assertEquals(0, $stats['pending_responses']);
    }
}
