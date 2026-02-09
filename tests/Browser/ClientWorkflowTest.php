<?php

namespace Tests\Browser;

use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ClientWorkflowTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->client()->create([
            'password' => bcrypt('password'),
        ]);
    }

    public function test_client_can_view_dashboard(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->client)
                ->visit('/client/dashboard')
                ->assertSee('Tableau de bord');
        });
    }

    public function test_client_can_create_project(): void
    {
        Specialty::factory()->create(['name' => 'Mariage']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->client)
                ->visit('/client/projects/create')
                ->assertSee('Nouveau projet')
                ->type('title', 'Mon projet photo de mariage')
                ->type('description', 'Je recherche un photographe pour mon mariage.')
                ->type('location', 'Paris')
                ->type('desired_date', now()->addMonth()->format('Y-m-d'))
                ->type('budget_min', '500')
                ->type('budget_max', '1500')
                ->press('Creer le projet')
                ->waitForText('Projet cree')
                ->assertSee('Mon projet photo de mariage');
        });
    }

    public function test_client_can_view_projects_list(): void
    {
        PhotoProject::factory()
            ->forClient($this->client)
            ->count(3)
            ->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->client)
                ->visit('/client/projects')
                ->assertSee('Mes projets');
        });
    }

    public function test_client_can_search_photographers(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create([
            'city' => 'Paris',
            'is_available' => true,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->client)
                ->visit('/search-photographers')
                ->assertSee('Rechercher')
                ->type('location', 'Paris')
                ->press('Rechercher')
                ->waitForText($browser->resolver->findOrFail('')->getText() !== '')
                ->assertPresent('.photographer-card');
        });
    }

    public function test_client_can_view_photographer_profile(): void
    {
        $user = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($user)->create([
            'bio' => 'Photographe professionnel avec 10 ans d\'experience.',
        ]);

        $this->browse(function (Browser $browser) use ($photographer) {
            $browser->loginAs($this->client)
                ->visit('/photographers/'.$photographer->id)
                ->assertSee($photographer->user->name)
                ->assertSee('Photographe professionnel');
        });
    }

    public function test_client_can_send_booking_request(): void
    {
        $project = PhotoProject::factory()
            ->forClient($this->client)
            ->published()
            ->create();

        $photographerUser = User::factory()->photographer()->create();
        $photographer = Photographer::factory()->withUser($photographerUser)->create();

        $this->browse(function (Browser $browser) use ($photographer) {
            $browser->loginAs($this->client)
                ->visit('/photographers/'.$photographer->id)
                ->press('Envoyer une demande')
                ->waitFor('#booking-modal')
                ->type('message', 'Bonjour, je suis interesse par vos services.')
                ->type('proposed_rate', '800')
                ->press('Envoyer')
                ->waitForText('Demande envoyee')
                ->assertSee('Demande envoyee');
        });
    }

    public function test_client_can_view_requests(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->client)
                ->visit('/client/requests')
                ->assertSee('Mes demandes');
        });
    }
}
