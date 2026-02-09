<?php

namespace Tests\Unit\Actions\Booking;

use App\Actions\Booking\CancelBookingRequestAction;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\User;
use App\Notifications\BookingRequestCancelled;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CancelBookingRequestActionTest extends TestCase
{
    use RefreshDatabase;

    private CancelBookingRequestAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CancelBookingRequestAction;
    }

    public function test_client_cancel_notifies_photographer(): void
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

        $this->action->execute($bookingRequest, $client);

        Notification::assertSentTo($photographerUser, BookingRequestCancelled::class);
        $this->assertDatabaseMissing('booking_requests', ['id' => $bookingRequest->id]);
    }

    public function test_photographer_cancel_notifies_client(): void
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

        $this->action->execute($bookingRequest, $photographerUser);

        Notification::assertSentTo($client, BookingRequestCancelled::class);
        $this->assertDatabaseMissing('booking_requests', ['id' => $bookingRequest->id]);
    }

    public function test_it_deletes_booking_request(): void
    {
        Notification::fake();

        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->create();
        $bookingRequest = BookingRequest::factory()->forProject($project)->create();

        $this->action->execute($bookingRequest, $client);

        $this->assertDatabaseMissing('booking_requests', ['id' => $bookingRequest->id]);
    }

    public function test_get_redirect_route_for_client(): void
    {
        $client = User::factory()->client()->create();

        $route = $this->action->getRedirectRoute($client);

        $this->assertEquals('client.requests.index', $route);
    }

    public function test_get_redirect_route_for_photographer(): void
    {
        $photographer = User::factory()->photographer()->create();

        $route = $this->action->getRedirectRoute($photographer);

        $this->assertEquals('photographer.requests.index', $route);
    }
}
