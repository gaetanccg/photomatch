<?php

namespace Tests\Unit\Transformers;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use App\Transformers\PhotographerMapTransformer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerMapTransformerTest extends TestCase
{
    use RefreshDatabase;

    private PhotographerMapTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new PhotographerMapTransformer;
    }

    public function test_transform_returns_correct_structure(): void
    {
        $photographer = Photographer::factory()->create([
            'location' => 'Paris, France',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'rating' => 4.5,
            'hourly_rate' => 100.00,
        ]);

        $result = $this->transformer->transform($photographer);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('location', $result);
        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertArrayHasKey('rating', $result);
        $this->assertArrayHasKey('hourly_rate', $result);
        $this->assertArrayHasKey('photo', $result);
        $this->assertArrayHasKey('url', $result);
    }

    public function test_transform_returns_correct_values(): void
    {
        $photographer = Photographer::factory()->create([
            'location' => 'Paris, France',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'rating' => 4.5,
            'hourly_rate' => 100.00,
        ]);

        $result = $this->transformer->transform($photographer);

        $this->assertEquals($photographer->id, $result['id']);
        $this->assertEquals($photographer->user->name, $result['name']);
        $this->assertEquals('Paris, France', $result['location']);
        $this->assertEquals(48.8566, $result['lat']);
        $this->assertEquals(2.3522, $result['lng']);
        $this->assertStringContainsString('photographers', $result['url']);
    }

    public function test_transform_uses_portfolio_image_as_photo(): void
    {
        $photographer = Photographer::factory()->create();
        PortfolioImage::factory()->forPhotographer($photographer)->create([
            'path' => 'portfolios/1/test.jpg',
        ]);

        $result = $this->transformer->transform($photographer->fresh(['portfolioImages']));

        $this->assertNotNull($result['photo']);
    }

    public function test_transform_collection_filters_without_coordinates(): void
    {
        $withCoords = Photographer::factory()->create([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        $withoutCoords = Photographer::factory()->create([
            'latitude' => null,
            'longitude' => null,
        ]);

        $collection = collect([$withCoords, $withoutCoords]);
        $result = $this->transformer->transformCollection($collection);

        $this->assertCount(1, $result);
        $this->assertEquals($withCoords->id, $result->first()['id']);
    }

    public function test_transform_collection_resets_keys(): void
    {
        Photographer::factory()->count(3)->create([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        $photographers = Photographer::all();
        $result = $this->transformer->transformCollection($photographers);

        $this->assertEquals([0, 1, 2], $result->keys()->toArray());
    }

    public function test_transform_casts_coordinates_to_float(): void
    {
        $photographer = Photographer::factory()->create([
            'latitude' => '48.8566',
            'longitude' => '2.3522',
        ]);

        $result = $this->transformer->transform($photographer);

        $this->assertIsFloat($result['lat']);
        $this->assertIsFloat($result['lng']);
    }

    public function test_transform_handles_null_photo(): void
    {
        $photographer = Photographer::factory()->create();

        $result = $this->transformer->transform($photographer);

        $this->assertArrayHasKey('photo', $result);
    }
}
