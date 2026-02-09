<?php

namespace Tests\Unit\Enums;

use App\Enums\ProjectType;
use PHPUnit\Framework\TestCase;

class ProjectTypeTest extends TestCase
{
    public function test_it_has_correct_cases(): void
    {
        $cases = ProjectType::cases();

        $this->assertCount(6, $cases);
        $this->assertContains(ProjectType::Event, $cases);
        $this->assertContains(ProjectType::Product, $cases);
        $this->assertContains(ProjectType::RealEstate, $cases);
        $this->assertContains(ProjectType::Corporate, $cases);
        $this->assertContains(ProjectType::Portrait, $cases);
        $this->assertContains(ProjectType::Other, $cases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertEquals('event', ProjectType::Event->value);
        $this->assertEquals('product', ProjectType::Product->value);
        $this->assertEquals('real_estate', ProjectType::RealEstate->value);
        $this->assertEquals('corporate', ProjectType::Corporate->value);
        $this->assertEquals('portrait', ProjectType::Portrait->value);
        $this->assertEquals('other', ProjectType::Other->value);
    }

    public function test_label_returns_french_translation(): void
    {
        $this->assertEquals('Événement', ProjectType::Event->label());
        $this->assertEquals('Produit', ProjectType::Product->label());
        $this->assertEquals('Immobilier', ProjectType::RealEstate->label());
        $this->assertEquals('Corporate', ProjectType::Corporate->label());
        $this->assertEquals('Portrait', ProjectType::Portrait->label());
        $this->assertEquals('Autre', ProjectType::Other->label());
    }

    public function test_options_returns_associative_array(): void
    {
        $options = ProjectType::options();

        $this->assertIsArray($options);
        $this->assertCount(6, $options);
        $this->assertArrayHasKey('event', $options);
        $this->assertEquals('Événement', $options['event']);
        $this->assertArrayHasKey('real_estate', $options);
        $this->assertEquals('Immobilier', $options['real_estate']);
    }

    public function test_can_be_created_from_value(): void
    {
        $type = ProjectType::from('event');

        $this->assertEquals(ProjectType::Event, $type);
    }

    public function test_try_from_returns_null_for_invalid_value(): void
    {
        $type = ProjectType::tryFrom('invalid');

        $this->assertNull($type);
    }
}
