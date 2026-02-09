<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\BookingStatus;
use App\Events\BookingRequestCreated;
use App\Events\BookingRequestResponded;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\User;
use App\Notifications\BookingRequestCancelled;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BookingRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_booking_request(): void
    {
        Event::fake();

        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        $response = $this->actingAs($client)
            ->post(route('booking-requests.store'), [
                'project_id' => $project->id,
                'photographer_id' => $photographer->id,
                'message' => 'Hello, I would like to book you.',
                'proposed_rate' => 500.00,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('booking_requests', [
            'project_id' => $project->id,
            'photographer_id' => $photographer->id,
            'status' => BookingStatus::Pending->value,
        ]);

        Event::assertDispatched(BookingRequestCreated::class);
    }

    public function test_client_cannot_create_duplicate_request(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->create();

        $response = $this->actingAs($client)
            ->post(route('booking-requests.store'), [
                'project_id' => $project->id,
                'photographer_id' => $photographer->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_client_cannot_send_request_for_draft_project(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->draft()->create();
        $photographer = Photographer::factory()->create();

        $response = $this->actingAs($client)
            ->post(route('booking-requests.store'), [
                'project_id' => $project->id,
                'photographer_id' => $photographer->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_photographer_can_view_requests_index(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->count(5)
            ->create();

        $response = $this->actingAs($user)
            ->get(route('photographer.requests.index'));

        $response->assertOk();
        $response->assertViewHas('requests');
    }

    public function test_photographer_can_filter_requests_by_status(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();

        BookingRequest::factory()->forPhotographer($photographer)->pending()->count(3)->create();
        BookingRequest::factory()->forPhotographer($photographer)->accepted()->count(2)->create();

        $response = $this->actingAs($user)
            ->get(route('photographer.requests.index', ['status' => 'pending']));

        $response->assertOk();
        $this->assertCount(3, $response->viewData('requests'));
    }

    public function test_photographer_can_accept_request(): void
    {
        Event::fake();

        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->pending()
            ->create();

        $response = $this->actingAs($user)
            ->put(route('photographer.requests.update', $bookingRequest), [
                'status' => 'accepted',
                'photographer_response' => 'I am available!',
                'proposed_price' => 600.00,
            ]);

        $response->assertRedirect(route('photographer.requests.index'));
        $response->assertSessionHas('success');

        $bookingRequest->refresh();
        $this->assertEquals(BookingStatus::Accepted, $bookingRequest->status);

        Event::assertDispatched(BookingRequestResponded::class);
    }

    public function test_photographer_can_decline_request(): void
    {
        Event::fake();

        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->pending()
            ->create();

        $response = $this->actingAs($user)
            ->put(route('photographer.requests.update', $bookingRequest), [
                'status' => 'declined',
                'photographer_response' => 'Sorry, not available.',
            ]);

        $response->assertRedirect(route('photographer.requests.index'));

        $bookingRequest->refresh();
        $this->assertEquals(BookingStatus::Declined, $bookingRequest->status);
    }

    public function test_photographer_cannot_respond_to_already_processed_request(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();
        $bookingRequest = BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->create();

        $response = $this->actingAs($user)
            ->put(route('photographer.requests.update', $bookingRequest), [
                'status' => 'declined',
            ]);

        // Policy blocks this action as request is already processed
        $response->assertForbidden();
    }

    public function test_photographer_can_view_history(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create();

        BookingRequest::factory()
            ->forPhotographer($photographer)
            ->accepted()
            ->count(5)
            ->create();

        $response = $this->actingAs($user)
            ->get(route('photographer.history.index'));

        $response->assertOk();
        $response->assertViewHas('missions');
        $response->assertViewHas('totalMissions');
        $response->assertViewHas('totalEarnings');
    }

    public function test_client_can_cancel_request(): void
    {
        Notification::fake();

        $client = User::factory()->client()->create();
        $photographerUser = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($photographerUser)->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->create();

        $response = $this->actingAs($client)
            ->delete(route('client.requests.destroy', $bookingRequest));

        $response->assertRedirect(route('client.requests.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('booking_requests', ['id' => $bookingRequest->id]);

        Notification::assertSentTo($photographerUser, BookingRequestCancelled::class);
    }

    public function test_photographer_can_cancel_request(): void
    {
        Notification::fake();

        $client = User::factory()->client()->create();
        $photographerUser = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($photographerUser)->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->create();

        $response = $this->actingAs($photographerUser)
            ->delete(route('photographer.requests.destroy', $bookingRequest));

        $response->assertRedirect(route('photographer.requests.index'));

        Notification::assertSentTo($client, BookingRequestCancelled::class);
    }
}
