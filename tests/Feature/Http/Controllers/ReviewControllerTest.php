<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_review_form(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->accepted()
            ->create();

        $response = $this->actingAs($client)
            ->get(route('client.reviews.create', $bookingRequest));

        $response->assertOk();
        $response->assertViewHas('bookingRequest');
    }

    public function test_client_cannot_review_others_booking(): void
    {
        $client = User::factory()->client()->create();
        $otherClient = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($otherClient)->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->accepted()
            ->create();

        $response = $this->actingAs($client)
            ->get(route('client.reviews.create', $bookingRequest));

        $response->assertForbidden();
    }

    public function test_client_cannot_review_non_accepted_booking(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->pending()
            ->create();

        $response = $this->actingAs($client)
            ->get(route('client.reviews.create', $bookingRequest));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_client_can_submit_review(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $photographer = Photographer::factory()->withoutRating()->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        $response = $this->actingAs($client)
            ->post(route('client.reviews.store', $bookingRequest), [
                'rating' => 5,
                'comment' => 'Excellent travail!',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'photographer_id' => $photographer->id,
            'rating' => 5,
        ]);

        $photographer->refresh();
        $this->assertEquals('5.0', $photographer->rating);
    }

    public function test_client_cannot_submit_duplicate_review(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $photographer = Photographer::factory()->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        Review::factory()->create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'photographer_id' => $photographer->id,
        ]);

        $response = $this->actingAs($client)
            ->post(route('client.reviews.store', $bookingRequest), [
                'rating' => 5,
                'comment' => 'Another review',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_photographer_can_view_response_form(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();
        $client = User::factory()->client()->create();
        $review = Review::factory()->create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'photographer_id' => $photographer->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('photographer.reviews.respond', $review));

        $response->assertOk();
        $response->assertViewHas('review');
    }

    public function test_photographer_cannot_respond_to_others_reviews(): void
    {
        $user = User::factory()->photographer()->create();
        Photographer::factory()->withUser($user)->create();

        $otherPhotographer = Photographer::factory()->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($otherPhotographer)
            ->accepted()
            ->create();
        $client = User::factory()->client()->create();
        $review = Review::factory()->create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'photographer_id' => $otherPhotographer->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('photographer.reviews.respond', $review));

        $response->assertForbidden();
    }

    public function test_photographer_can_submit_response(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();
        $client = User::factory()->client()->create();
        $review = Review::factory()->create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'photographer_id' => $photographer->id,
            'photographer_response' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('photographer.reviews.respond.store', $review), [
                'photographer_response' => 'Merci beaucoup!',
            ]);

        $response->assertRedirect(route('photographer.reviews.index'));
        $response->assertSessionHas('success');

        $review->refresh();
        $this->assertEquals('Merci beaucoup!', $review->photographer_response);
        $this->assertNotNull($review->responded_at);
    }

    public function test_photographer_can_view_reviews_index(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $client = User::factory()->client()->create();

        for ($i = 0; $i < 5; $i++) {
            $bookingRequest = BookingRequest::factory()
                ->forPhotographer($photographer)
                ->accepted()
                ->create();
            Review::factory()->create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $client->id,
                'photographer_id' => $photographer->id,
            ]);
        }

        $response = $this->actingAs($user)
            ->get(route('photographer.reviews.index'));

        $response->assertOk();
        $response->assertViewHas('reviews');
        $response->assertViewHas('stats');
    }

    public function test_reviews_index_calculates_stats(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $client = User::factory()->client()->create();

        for ($i = 0; $i < 3; $i++) {
            $bookingRequest = BookingRequest::factory()
                ->forPhotographer($photographer)
                ->accepted()
                ->create();
            Review::factory()->create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $client->id,
                'photographer_id' => $photographer->id,
                'rating' => 5,
                'photographer_response' => 'Thanks!',
                'responded_at' => now(),
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            $bookingRequest = BookingRequest::factory()
                ->forPhotographer($photographer)
                ->accepted()
                ->create();
            Review::factory()->create([
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $client->id,
                'photographer_id' => $photographer->id,
                'rating' => 4,
                'photographer_response' => null,
            ]);
        }

        $response = $this->actingAs($user)
            ->get(route('photographer.reviews.index'));

        $response->assertOk();
        $stats = $response->viewData('stats');
        $this->assertEquals(5, $stats['total_reviews']);
        $this->assertEquals(3, $stats['five_star']);
        $this->assertEquals(2, $stats['pending_responses']);
    }
}
