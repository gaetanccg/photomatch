<?php

namespace Tests\Unit\Services;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use App\Services\PortfolioUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    private PortfolioUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PortfolioUploadService();
        Storage::fake('s3');
    }

    public function test_upload_single_image_creates_record(): void
    {
        $photographer = Photographer::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $image = $this->service->uploadSingleImage($photographer, $file, 1);

        $this->assertInstanceOf(PortfolioImage::class, $image);
        $this->assertEquals($photographer->id, $image->photographer_id);
        $this->assertEquals(1, $image->sort_order);
        $this->assertNotNull($image->path);
        $this->assertDatabaseHas('portfolio_images', ['id' => $image->id]);
    }

    public function test_upload_single_image_stores_file_in_s3(): void
    {
        $photographer = Photographer::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $image = $this->service->uploadSingleImage($photographer, $file, 1);

        Storage::disk('s3')->assertExists($image->path);
    }

    public function test_upload_images_handles_multiple_files(): void
    {
        $photographer = Photographer::factory()->create();
        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
            UploadedFile::fake()->image('photo3.jpg'),
        ];

        $images = $this->service->uploadImages($photographer, $files);

        $this->assertCount(3, $images);
        $this->assertEquals(3, $photographer->portfolioImages()->count());
    }

    public function test_upload_images_increments_sort_order(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->create(['sort_order' => 5]);

        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
        ];

        $images = $this->service->uploadImages($photographer, $files);

        $this->assertEquals(6, $images[0]->sort_order);
        $this->assertEquals(7, $images[1]->sort_order);
    }

    public function test_can_upload_returns_empty_when_under_limit(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->count(10)->create();

        $errors = $this->service->canUpload($photographer, 5);

        $this->assertEmpty($errors);
    }

    public function test_can_upload_returns_error_when_exceeds_limit(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->count(48)->create();

        $errors = $this->service->canUpload($photographer, 5);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('50', $errors[0]);
    }

    public function test_get_remaining_slots_calculates_correctly(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->count(30)->create();

        $remaining = $this->service->getRemainingSlots($photographer);

        $this->assertEquals(20, $remaining);
    }

    public function test_get_remaining_slots_returns_zero_when_full(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->count(50)->create();

        $remaining = $this->service->getRemainingSlots($photographer);

        $this->assertEquals(0, $remaining);
    }

    public function test_max_constants_are_defined(): void
    {
        $this->assertEquals(50, PortfolioUploadService::MAX_IMAGES_PER_PORTFOLIO);
        $this->assertEquals(10, PortfolioUploadService::MAX_IMAGES_PER_UPLOAD);
    }
}
