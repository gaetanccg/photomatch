<?php

namespace Tests\Browser;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotographerWorkflowTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $photographerUser;

    protected Photographer $photographer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->photographerUser = User::factory()->photographer()->create([
            'password' => bcrypt('password'),
        ]);
        $this->photographer = Photographer::factory()
            ->withUser($this->photographerUser)
            ->create();
    }

    public function test_photographer_can_view_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/dashboard')
                ->assertSee('Tableau de bord');
        });
    }

    public function test_photographer_can_view_profile_edit(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/profile')
                ->assertSee('Mon profil');
        });
    }

    public function test_photographer_can_update_profile(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/profile')
                ->type('bio', 'Ma nouvelle biographie de photographe professionnel.')
                ->type('city', 'Lyon')
                ->press('Enregistrer')
                ->waitForText('Profil mis a jour')
                ->assertSee('Profil mis a jour');
        });
    }

    public function test_photographer_can_view_requests(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();

        BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($this->photographer)
            ->pending()
            ->count(3)
            ->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/requests')
                ->assertSee('Demandes recues');
        });
    }

    public function test_photographer_can_accept_request(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();

        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($this->photographer)
            ->pending()
            ->create();

        $this->browse(function (Browser $browser) use ($bookingRequest) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/requests/'.$bookingRequest->id)
                ->assertSee('Details de la demande')
                ->type('photographer_response', 'Je suis disponible et interesse!')
                ->type('proposed_price', '750')
                ->press('Accepter')
                ->waitForText('Demande acceptee')
                ->assertSee('Demande acceptee');
        });

        $this->assertEquals(BookingStatus::Accepted, $bookingRequest->fresh()->status);
    }

    public function test_photographer_can_decline_request(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();

        $bookingRequest = BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($this->photographer)
            ->pending()
            ->create();

        $this->browse(function (Browser $browser) use ($bookingRequest) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/requests/'.$bookingRequest->id)
                ->type('photographer_response', 'Desole, je ne suis pas disponible a cette date.')
                ->press('Refuser')
                ->waitForText('Demande refusee')
                ->assertSee('Demande refusee');
        });

        $this->assertEquals(BookingStatus::Declined, $bookingRequest->fresh()->status);
    }

    public function test_photographer_can_view_portfolio(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/portfolio')
                ->assertSee('Mon portfolio');
        });
    }

    public function test_photographer_can_view_availabilities(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/availabilities')
                ->assertSee('Disponibilites');
        });
    }

    public function test_photographer_can_view_history(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/history')
                ->assertSee('Historique');
        });
    }

    public function test_photographer_can_filter_requests_by_status(): void
    {
        $client = User::factory()->client()->create();
        $project = PhotoProject::factory()->forClient($client)->published()->create();

        BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($this->photographer)
            ->pending()
            ->count(2)
            ->create();

        BookingRequest::factory()
            ->forProject($project)
            ->forPhotographer($this->photographer)
            ->accepted()
            ->count(3)
            ->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->photographerUser)
                ->visit('/photographer/requests?status=pending')
                ->assertSee('Demandes recues');
        });
    }
}
