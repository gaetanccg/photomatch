<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_view_login_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Connexion')
                ->assertSee('Adresse email')
                ->assertSee('Mot de passe');
        });
    }

    public function test_user_can_login_as_client(): void
    {
        $user = User::factory()->client()->create([
            'email' => 'client@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Se connecter')
                ->waitForLocation('/search-photographers')
                ->assertPathIs('/search-photographers');
        });
    }

    public function test_user_can_login_as_photographer(): void
    {
        $user = User::factory()->photographer()->create([
            'email' => 'photo@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Se connecter')
                ->waitForLocation('/photographer/dashboard')
                ->assertPathIs('/photographer/dashboard');
        });
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'invalid@test.com')
                ->type('password', 'wrongpassword')
                ->press('Se connecter')
                ->assertPathIs('/login')
                ->assertSee('Ces identifiants ne correspondent pas');
        });
    }

    public function test_user_can_view_registration_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Creer un compte')
                ->assertSee('Client')
                ->assertSee('Photographe');
        });
    }

    public function test_user_can_register_as_client(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->radio('role', 'client')
                ->type('name', 'Test Client')
                ->type('email', 'newclient@test.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->press('Creer mon compte')
                ->waitForLocation('/dashboard')
                ->assertAuthenticated();
        });
    }

    public function test_user_can_register_as_photographer(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->radio('role', 'photographer')
                ->waitFor('#siret-field:not(.hidden)')
                ->type('siret', '12345678901234')
                ->type('name', 'Test Photographer')
                ->type('email', 'newphoto@test.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->press('Creer mon compte')
                ->waitForLocation('/dashboard')
                ->assertAuthenticated();
        });
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->client()->create([
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/search-photographers')
                ->click('@user-menu-button')
                ->waitFor('@logout-button')
                ->click('@logout-button')
                ->waitForLocation('/')
                ->assertGuest();
        });
    }
}
