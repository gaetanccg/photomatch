<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Photographer;
use App\Models\PhotographerTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerTagsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Photographer $photographer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->photographer()->create();
        $this->photographer = Photographer::factory()->withUser($this->user)->create();
    }

    public function test_edit_page_displays_tags(): void
    {
        PhotographerTag::factory()->forPhotographer($this->photographer)->create(['name' => 'mariage']);
        PhotographerTag::factory()->forPhotographer($this->photographer)->create(['name' => 'portrait']);

        $response = $this->actingAs($this->user)->get(route('photographer.profile.edit'));

        $response->assertOk();
        $response->assertSee('mariage');
        $response->assertSee('portrait');
        $response->assertSee('Mes tags');
    }

    public function test_update_tags_creates_new_tags(): void
    {
        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => ['mariage', 'portrait', 'corporate'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('photographer_tags', 3);
        $this->assertDatabaseHas('photographer_tags', [
            'photographer_id' => $this->photographer->id,
            'name' => 'mariage',
        ]);
        $this->assertDatabaseHas('photographer_tags', [
            'photographer_id' => $this->photographer->id,
            'name' => 'portrait',
        ]);
    }

    public function test_update_tags_replaces_existing_tags(): void
    {
        PhotographerTag::factory()->forPhotographer($this->photographer)->create(['name' => 'ancien-tag']);

        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => ['nouveau-tag'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('photographer_tags', ['name' => 'ancien-tag']);
        $this->assertDatabaseHas('photographer_tags', ['name' => 'nouveau-tag']);
        $this->assertDatabaseCount('photographer_tags', 1);
    }

    public function test_update_tags_with_empty_array_deletes_all(): void
    {
        PhotographerTag::factory()->forPhotographer($this->photographer)->count(3)->create();

        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('photographer_tags', 0);
    }

    public function test_update_tags_deduplicates(): void
    {
        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => ['mariage', 'mariage', 'portrait'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('photographer_tags', 2);
    }

    public function test_update_tags_trims_whitespace(): void
    {
        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => ['  mariage  ', ' portrait '],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('photographer_tags', ['name' => 'mariage']);
        $this->assertDatabaseHas('photographer_tags', ['name' => 'portrait']);
    }

    public function test_update_tags_validates_max_15(): void
    {
        $tags = array_map(fn ($i) => "tag-{$i}", range(1, 16));

        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => $tags,
        ]);

        $response->assertSessionHasErrors('tags');
    }

    public function test_update_tags_validates_min_length(): void
    {
        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => ['a'],
        ]);

        $response->assertSessionHasErrors('tags.0');
    }

    public function test_update_tags_validates_max_length(): void
    {
        $response = $this->actingAs($this->user)->put(route('photographer.profile.tags'), [
            'tags' => [str_repeat('a', 51)],
        ]);

        $response->assertSessionHasErrors('tags.0');
    }

    public function test_update_tags_requires_authentication(): void
    {
        $response = $this->put(route('photographer.profile.tags'), [
            'tags' => ['mariage'],
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_update_tags_requires_photographer_role(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)->put(route('photographer.profile.tags'), [
            'tags' => ['mariage'],
        ]);

        $response->assertRedirect(route('search.index'));
    }

    public function test_public_profile_shows_tags(): void
    {
        PhotographerTag::factory()->forPhotographer($this->photographer)->create(['name' => 'mariage']);
        PhotographerTag::factory()->forPhotographer($this->photographer)->create(['name' => 'portrait']);

        $response = $this->get(route('photographers.show', $this->photographer));

        $response->assertOk();
        $response->assertSee('mariage');
        $response->assertSee('portrait');
    }
}
