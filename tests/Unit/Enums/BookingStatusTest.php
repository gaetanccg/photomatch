<?php

namespace Tests\Unit\Enums;

use App\Enums\BookingStatus;
use PHPUnit\Framework\TestCase;

class BookingStatusTest extends TestCase
{
    public function test_it_has_correct_cases(): void
    {
        $cases = BookingStatus::cases();

        $this->assertCount(4, $cases);
        $this->assertContains(BookingStatus::Pending, $cases);
        $this->assertContains(BookingStatus::Accepted, $cases);
        $this->assertContains(BookingStatus::Declined, $cases);
        $this->assertContains(BookingStatus::Cancelled, $cases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertEquals('pending', BookingStatus::Pending->value);
        $this->assertEquals('accepted', BookingStatus::Accepted->value);
        $this->assertEquals('declined', BookingStatus::Declined->value);
        $this->assertEquals('cancelled', BookingStatus::Cancelled->value);
    }

    public function test_label_returns_french_translation(): void
    {
        $this->assertEquals('En attente', BookingStatus::Pending->label());
        $this->assertEquals('Acceptée', BookingStatus::Accepted->label());
        $this->assertEquals('Déclinée', BookingStatus::Declined->label());
        $this->assertEquals('Annulée', BookingStatus::Cancelled->label());
    }

    public function test_color_returns_correct_color(): void
    {
        $this->assertEquals('yellow', BookingStatus::Pending->color());
        $this->assertEquals('green', BookingStatus::Accepted->color());
        $this->assertEquals('red', BookingStatus::Declined->color());
        $this->assertEquals('gray', BookingStatus::Cancelled->color());
    }

    public function test_options_returns_associative_array(): void
    {
        $options = BookingStatus::options();

        $this->assertIsArray($options);
        $this->assertCount(4, $options);
        $this->assertArrayHasKey('pending', $options);
        $this->assertEquals('En attente', $options['pending']);
        $this->assertArrayHasKey('accepted', $options);
        $this->assertEquals('Acceptée', $options['accepted']);
    }

    public function test_can_be_created_from_value(): void
    {
        $status = BookingStatus::from('accepted');

        $this->assertEquals(BookingStatus::Accepted, $status);
    }

    public function test_try_from_returns_null_for_invalid_value(): void
    {
        $status = BookingStatus::tryFrom('invalid');

        $this->assertNull($status);
    }
}
