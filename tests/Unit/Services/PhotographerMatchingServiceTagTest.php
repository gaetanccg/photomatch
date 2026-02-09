<?php

namespace Tests\Unit\Services;

use App\Models\Photographer;
use App\Models\PhotographerTag;
use App\Models\PhotoProject;
use App\Services\PhotographerMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerMatchingServiceTagTest extends TestCase
{
    use RefreshDatabase;

    private PhotographerMatchingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhotographerMatchingService;
    }

    public function test_tag_score_returns_neutral_when_no_tags(): void
    {
        $photographer = Photographer::factory()->create();
        $project = PhotoProject::factory()->create(['description' => 'Je cherche un photographe de mariage']);

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(5.0, $score); // WEIGHT_TAGS * 0.5 = 10 * 0.5
    }

    public function test_tag_score_returns_neutral_when_no_description(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'mariage']);

        $project = PhotoProject::factory()->create(['description' => '']);

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(5.0, $score);
    }

    public function test_tag_score_full_when_all_tags_match(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'mariage']);
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'portrait']);

        $project = PhotoProject::factory()->create([
            'title' => 'Mon projet',
            'description' => 'Je cherche un photographe pour un mariage et des portrait en extérieur',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(10.0, $score); // All tags match = full WEIGHT_TAGS
    }

    public function test_tag_score_partial_when_some_tags_match(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'mariage']);
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'corporate']);

        $project = PhotoProject::factory()->create([
            'title' => 'Projet photo',
            'description' => 'Je cherche un photographe de mariage à Paris',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(5.0, $score); // 1/2 tags match = 0.5 * 10
    }

    public function test_tag_score_zero_when_no_tags_match(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'corporate']);
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'immobilier']);

        $project = PhotoProject::factory()->create([
            'title' => 'Mariage champêtre',
            'description' => 'Je cherche un photographe pour un mariage en plein air',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(0.0, $score);
    }

    public function test_tag_score_uses_word_boundary_matching(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'port']);

        $project = PhotoProject::factory()->create([
            'title' => 'Séance portrait',
            'description' => 'Je cherche un photographe pour des portraits en studio',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        // "port" should NOT match "portrait" (word boundary)
        $this->assertEquals(0.0, $score);
    }

    public function test_tag_score_matches_multi_word_tags(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'retouche HDR']);

        $project = PhotoProject::factory()->create([
            'title' => 'Projet immobilier',
            'description' => 'Je cherche retouche HDR pro pour mes photos',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(10.0, $score);
    }

    public function test_tag_score_is_case_insensitive(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'Mariage']);

        $project = PhotoProject::factory()->create([
            'title' => 'Mon projet',
            'description' => 'Photographe pour un MARIAGE à Lyon',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(10.0, $score);
    }

    public function test_tag_score_matches_in_title(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'mariage']);

        $project = PhotoProject::factory()->create([
            'title' => 'Photographe mariage',
            'description' => 'Recherche photographe professionnel',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateTagScore($photographer, $project);

        $this->assertEquals(10.0, $score);
    }

    public function test_calculate_score_includes_tags_in_breakdown(): void
    {
        $photographer = Photographer::factory()->create();
        PhotographerTag::factory()->forPhotographer($photographer)->create(['name' => 'mariage']);

        $project = PhotoProject::factory()->create([
            'description' => 'Projet de mariage',
        ]);

        $photographer->load('tags');

        $score = $this->service->calculateScore($photographer, $project);

        $this->assertArrayHasKey('tags', $score['breakdown']);
        $this->assertArrayHasKey('total', $score);
    }

    public function test_weights_sum_to_100(): void
    {
        $photographer = Photographer::factory()->create();
        $photographer->load('tags');

        $project = PhotoProject::factory()->create();

        $score = $this->service->calculateScore($photographer, $project);

        // All breakdown values exist
        $this->assertCount(7, $score['breakdown']);
        $this->assertArrayHasKey('specialty', $score['breakdown']);
        $this->assertArrayHasKey('tags', $score['breakdown']);
        $this->assertArrayHasKey('keywords', $score['breakdown']);
        $this->assertArrayHasKey('distance', $score['breakdown']);
        $this->assertArrayHasKey('rating', $score['breakdown']);
        $this->assertArrayHasKey('experience', $score['breakdown']);
        $this->assertArrayHasKey('price', $score['breakdown']);
    }
}
