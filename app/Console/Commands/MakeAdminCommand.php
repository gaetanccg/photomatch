<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin
                            {--email= : Email de l\'admin}
                            {--name= : Nom de l\'admin}
                            {--password= : Mot de passe}';

    protected $description = 'Creer un utilisateur administrateur';

    public function handle(): int
    {
        $this->info('Creation d\'un compte administrateur');
        $this->newLine();

        // Recuperer les valeurs (options ou interactif)
        $email = $this->option('email') ?? $this->ask('Email de l\'admin');
        $name = $this->option('name') ?? $this->ask('Nom de l\'admin');
        $password = $this->option('password') ?? $this->secret('Mot de passe');

        // Validation
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string', 'min:2'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email n\'est pas valide.',
            'email.unique' => 'Cet email est deja utilise.',
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit faire au moins 2 caracteres.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit faire au moins 6 caracteres.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        }

        // Recap avant creation
        $this->newLine();
        $this->info('Recapitulatif :');
        $this->table(
            ['Champ', 'Valeur'],
            [
                ['Email', $email],
                ['Nom', $name],
                ['Role', 'admin'],
            ]
        );

        if (! $this->option('email') && ! $this->confirm('Confirmer la creation ?', true)) {
            $this->warn('Creation annulee.');

            return Command::SUCCESS;
        }

        // Creation de l'admin
        $user = User::create([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'role' => 'admin',
        ]);

        $this->newLine();
        $this->info('Admin cree avec succes !');
        $this->line("ID: {$user->id}");
        $this->line("Email: {$user->email}");
        $this->line('Connexion: /login');

        return Command::SUCCESS;
    }
}
