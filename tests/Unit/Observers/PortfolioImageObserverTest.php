<?php

namespace Tests\Unit\Observers;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioImageObserverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    public function test_deleting_removes_file_from_s3(): void
    {
        $photographer = Photographer::factory()->create();

        Storage::disk('s3')->put('portfolios/1/test.jpg', 'content');

        $image = PortfolioImage::factory()->forPhotographer($photographer)->create([
            'path' => 'portfolios/1/test.jpg',
        ]);

        Storage::disk('s3')->assertExists('portfolios/1/test.jpg');

        $image->delete();

        Storage::disk('s3')->assertMissing('portfolios/1/test.jpg');
    }

    public function test_deleting_removes_thumbnail_from_s3(): void
    {
        $photographer = Photographer::factory()->create();

        Storage::disk('s3')->put('portfolios/1/test.jpg', 'content');
        Storage::disk('s3')->put('portfolios/1/thumbnails/thumb_test.jpg', 'content');

        $image = PortfolioImage::factory()->forPhotographer($photographer)->create([
            'path' => 'portfolios/1/test.jpg',
            'thumbnail_path' => 'portfolios/1/thumbnails/thumb_test.jpg',
        ]);

        $image->delete();

        Storage::disk('s3')->assertMissing('portfolios/1/test.jpg');
        Storage::disk('s3')->assertMissing('portfolios/1/thumbnails/thumb_test.jpg');
    }

    public function test_deleting_handles_null_thumbnail_path(): void
    {
        $photographer = Photographer::factory()->create();

        Storage::disk('s3')->put('portfolios/1/test.jpg', 'content');

        $image = PortfolioImage::factory()->forPhotographer($photographer)->create([
            'path' => 'portfolios/1/test.jpg',
            'thumbnail_path' => null,
        ]);

        $image->delete();

        Storage::disk('s3')->assertMissing('portfolios/1/test.jpg');
        $this->assertDatabaseMissing('portfolio_images', ['id' => $image->id]);
    }

    public function test_deleting_handles_missing_files_gracefully(): void
    {
        $photographer = Photographer::factory()->create();

        $image = PortfolioImage::factory()->forPhotographer($photographer)->create([
            'path' => 'portfolios/1/nonexistent.jpg',
        ]);

        $image->delete();

        $this->assertDatabaseMissing('portfolio_images', ['id' => $image->id]);
    }

    public function test_observer_is_registered(): void
    {
        $photographer = Photographer::factory()->create();

        Storage::disk('s3')->put('portfolios/1/test.jpg', 'content');

        $image = PortfolioImage::create([
            'photographer_id' => $photographer->id,
            'filename' => 'test.jpg',
            'original_name' => 'test.jpg',
            'path' => 'portfolios/1/test.jpg',
            'sort_order' => 1,
        ]);

        $image->delete();

        Storage::disk('s3')->assertMissing('portfolios/1/test.jpg');
    }
}
