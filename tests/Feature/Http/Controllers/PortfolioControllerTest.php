<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Photographer $photographer;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');

        $this->user = User::factory()->photographer()->create();
        $this->photographer = Photographer::factory()->withUser($this->user)->create();
    }

    public function test_index_displays_portfolio_images(): void
    {
        PortfolioImage::factory()
            ->forPhotographer($this->photographer)
            ->count(5)
            ->create();

        $response = $this->actingAs($this->user)
            ->get(route('photographer.portfolio.index'));

        $response->assertOk();
        $response->assertViewHas('images');
        $response->assertViewHas('specialties');
    }

    public function test_store_uploads_images(): void
    {
        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
        ];

        $response = $this->actingAs($this->user)
            ->post(route('photographer.portfolio.store'), [
                'images' => $files,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals(2, $this->photographer->portfolioImages()->count());
    }

    public function test_store_validates_image_format(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post(route('photographer.portfolio.store'), [
                'images' => [$file],
            ]);

        $response->assertSessionHasErrors('images.0');
    }

    public function test_store_validates_image_size(): void
    {
        $file = UploadedFile::fake()->image('large.jpg')->size(6000);

        $response = $this->actingAs($this->user)
            ->post(route('photographer.portfolio.store'), [
                'images' => [$file],
            ]);

        $response->assertSessionHasErrors('images.0');
    }

    public function test_store_rejects_when_portfolio_full(): void
    {
        PortfolioImage::factory()
            ->forPhotographer($this->photographer)
            ->count(50)
            ->create();

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($this->user)
            ->post(route('photographer.portfolio.store'), [
                'images' => [$file],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_update_modifies_image_metadata(): void
    {
        $specialty = Specialty::factory()->create();
        $image = PortfolioImage::factory()
            ->forPhotographer($this->photographer)
            ->create();

        $response = $this->actingAs($this->user)
            ->put(route('photographer.portfolio.update', $image), [
                'caption' => 'New caption',
                'specialty_id' => $specialty->id,
                'is_featured' => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $image->refresh();
        $this->assertEquals('New caption', $image->caption);
        $this->assertEquals($specialty->id, $image->specialty_id);
        $this->assertTrue($image->is_featured);
    }

    public function test_update_rejects_other_users_images(): void
    {
        $otherPhotographer = Photographer::factory()->create();
        $image = PortfolioImage::factory()
            ->forPhotographer($otherPhotographer)
            ->create();

        $response = $this->actingAs($this->user)
            ->put(route('photographer.portfolio.update', $image), [
                'caption' => 'Hacked caption',
            ]);

        $response->assertForbidden();
    }

    public function test_destroy_deletes_image(): void
    {
        Storage::disk('s3')->put('portfolios/1/test.jpg', 'content');

        $image = PortfolioImage::factory()
            ->forPhotographer($this->photographer)
            ->create(['path' => 'portfolios/1/test.jpg']);

        $response = $this->actingAs($this->user)
            ->delete(route('photographer.portfolio.destroy', $image));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('portfolio_images', ['id' => $image->id]);
        Storage::disk('s3')->assertMissing('portfolios/1/test.jpg');
    }

    public function test_destroy_rejects_other_users_images(): void
    {
        $otherPhotographer = Photographer::factory()->create();
        $image = PortfolioImage::factory()
            ->forPhotographer($otherPhotographer)
            ->create();

        $response = $this->actingAs($this->user)
            ->delete(route('photographer.portfolio.destroy', $image));

        $response->assertForbidden();
    }

    public function test_reorder_updates_sort_order(): void
    {
        $images = PortfolioImage::factory()
            ->forPhotographer($this->photographer)
            ->count(3)
            ->create();

        $newOrder = $images->pluck('id')->reverse()->values()->toArray();

        $response = $this->actingAs($this->user)
            ->post(route('photographer.portfolio.reorder'), [
                'order' => $newOrder,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        foreach ($newOrder as $position => $imageId) {
            $this->assertDatabaseHas('portfolio_images', [
                'id' => $imageId,
                'sort_order' => $position,
            ]);
        }
    }

    public function test_guest_cannot_access_portfolio(): void
    {
        $response = $this->get(route('photographer.portfolio.index'));

        $response->assertRedirect(route('login'));
    }
}
