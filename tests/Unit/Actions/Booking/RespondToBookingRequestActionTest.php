<?php

namespace Tests\Unit\Actions\Booking;

use App\Actions\Booking\RespondToBookingRequestAction;
use App\Enums\BookingStatus;
use App\Events\BookingRequestResponded;
use App\Models\BookingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RespondToBookingRequestActionTest extends TestCase
{
    use RefreshDatabase;

    private RespondToBookingRequestAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RespondToBookingRequestAction;
    }

    public function test_it_accepts_booking_request(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create();

        $result = $this->action->execute(
            $bookingRequest,
            'accepted',
            'Je suis disponible!',
            600.00
        );

        $this->assertEquals(BookingStatus::Accepted, $result->status);
        $this->assertEquals('Je suis disponible!', $result->photographer_response);
        $this->assertEquals(600.00, $result->proposed_price);
        $this->assertNotNull($result->responded_at);
    }

    public function test_it_declines_booking_request(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create();

        $result = $this->action->execute(
            $bookingRequest,
            BookingStatus::Declined,
            'Je ne suis pas disponible.'
        );

        $this->assertEquals(BookingStatus::Declined, $result->status);
        $this->assertEquals('Je ne suis pas disponible.', $result->photographer_response);
        $this->assertNotNull($result->responded_at);
    }

    public function test_it_dispatches_event(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create();

        $this->action->execute($bookingRequest, 'accepted');

        Event::assertDispatched(BookingRequestResponded::class);
    }

    public function test_it_accepts_string_status(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create();

        $result = $this->action->execute($bookingRequest, 'accepted');

        $this->assertEquals(BookingStatus::Accepted, $result->status);
    }

    public function test_it_accepts_enum_status(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create();

        $result = $this->action->execute($bookingRequest, BookingStatus::Accepted);

        $this->assertEquals(BookingStatus::Accepted, $result->status);
    }

    public function test_can_respond_returns_true_for_pending(): void
    {
        $bookingRequest = BookingRequest::factory()->pending()->create();

        $this->assertTrue($this->action->canRespond($bookingRequest));
    }

    public function test_can_respond_returns_false_for_accepted(): void
    {
        $bookingRequest = BookingRequest::factory()->accepted()->create();

        $this->assertFalse($this->action->canRespond($bookingRequest));
    }

    public function test_can_respond_returns_false_for_declined(): void
    {
        $bookingRequest = BookingRequest::factory()->declined()->create();

        $this->assertFalse($this->action->canRespond($bookingRequest));
    }

    public function test_proposed_price_only_updated_on_acceptance(): void
    {
        Event::fake();

        $bookingRequest = BookingRequest::factory()->pending()->create([
            'proposed_price' => 500.00,
        ]);

        $result = $this->action->execute(
            $bookingRequest,
            BookingStatus::Declined,
            'Non merci.',
            999.00
        );

        $this->assertEquals(500.00, $result->proposed_price);
    }
}
