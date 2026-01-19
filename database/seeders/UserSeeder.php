<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@photomatch.test'],
            [
                'name' => 'Admin PhotoMatch',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Clients
        $clients = [
            ['name' => 'Marie Dupont', 'email' => 'marie.dupont@example.com'],
            ['name' => 'Jean Martin', 'email' => 'jean.martin@example.com'],
            ['name' => 'Sophie Bernard', 'email' => 'sophie.bernard@example.com'],
            ['name' => 'Pierre Durand', 'email' => 'pierre.durand@example.com'],
            ['name' => 'Isabelle Moreau', 'email' => 'isabelle.moreau@example.com'],
        ];

        foreach ($clients as $client) {
            User::firstOrCreate(
                ['email' => $client['email']],
                [
                    'name' => $client['name'],
                    'password' => Hash::make('password'),
                    'role' => 'client',
                ]
            );
        }

        // Add the default test client
        User::firstOrCreate(
            ['email' => 'client@photomatch.test'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // Photographers
        $photographers = [
            ['name' => 'Lucas Petit', 'email' => 'lucas.petit@example.com'],
            ['name' => 'Emma Leroy', 'email' => 'emma.leroy@example.com'],
            ['name' => 'Hugo Roux', 'email' => 'hugo.roux@example.com'],
            ['name' => 'Chloé Fournier', 'email' => 'chloe.fournier@example.com'],
            ['name' => 'Nathan Girard', 'email' => 'nathan.girard@example.com'],
            ['name' => 'Léa Bonnet', 'email' => 'lea.bonnet@example.com'],
            ['name' => 'Thomas Michel', 'email' => 'thomas.michel@example.com'],
            ['name' => 'Camille Lambert', 'email' => 'camille.lambert@example.com'],
            ['name' => 'Maxime Faure', 'email' => 'maxime.faure@example.com'],
            ['name' => 'Julie Mercier', 'email' => 'julie.mercier@example.com'],
        ];

        foreach ($photographers as $photographer) {
            User::firstOrCreate(
                ['email' => $photographer['email']],
                [
                    'name' => $photographer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'photographer',
                ]
            );
        }

        // Add the default test photographer
        User::firstOrCreate(
            ['email' => 'photographe@photomatch.test'],
            [
                'name' => 'Photographe Test',
                'password' => Hash::make('password'),
                'role' => 'photographer',
            ]
        );
    }
}
