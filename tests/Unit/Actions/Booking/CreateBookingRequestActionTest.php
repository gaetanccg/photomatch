<?php

namespace Tests\Unit\Actions\Booking;

use App\Actions\Booking\CreateBookingRequestAction;
use App\Enums\BookingStatus;
use App\Events\BookingRequestCreated;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateBookingRequestActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateBookingRequestAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateBookingRequestAction;
    }

    public function test_it_creates_booking_request(): void
    {
        Event::fake();

        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        $bookingRequest = $this->action->execute(
            $project,
            $photographer,
            'Test message',
            500.00
        );

        $this->assertInstanceOf(BookingRequest::class, $bookingRequest);
        $this->assertEquals($project->id, $bookingRequest->project_id);
        $this->assertEquals($photographer->id, $bookingRequest->photographer_id);
        $this->assertEquals(BookingStatus::Pending, $bookingRequest->status);
        $this->assertEquals('Test message', $bookingRequest->client_message);
        $this->assertEquals(500.00, $bookingRequest->proposed_price);
        $this->assertNotNull($bookingRequest->sent_at);
    }

    public function test_it_dispatches_event(): void
    {
        Event::fake();

        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        $this->action->execute($project, $photographer, 'Test message');

        Event::assertDispatched(BookingRequestCreated::class);
    }

    public function test_can_create_returns_empty_for_valid_request(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        $errors = $this->action->canCreate($project, $photographer);

        $this->assertEmpty($errors);
    }

    public function test_can_create_returns_error_for_draft_project(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->draft()->create();
        $photographer = Photographer::factory()->create();

        $errors = $this->action->canCreate($project, $photographer);

        $this->assertNotEmpty($errors);
        $this->assertContains('Le projet doit être publié pour envoyer une demande.', $errors);
    }

    public function test_can_create_returns_error_for_existing_request(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($photographer)
            ->create();

        $errors = $this->action->canCreate($project, $photographer);

        $this->assertNotEmpty($errors);
        $this->assertContains('Une demande a déjà été envoyée à ce photographe pour ce projet.', $errors);
    }

    public function test_it_creates_request_without_optional_fields(): void
    {
        Event::fake();

        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();
        $photographer = Photographer::factory()->create();

        $bookingRequest = $this->action->execute($project, $photographer);

        $this->assertNull($bookingRequest->client_message);
        $this->assertNull($bookingRequest->proposed_price);
    }
}
