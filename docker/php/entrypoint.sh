#!/bin/bash
set -e

echo "========================================"
echo "  PhotoMatch - Initialisation Docker"
echo "========================================"

# Function to wait for MySQL
wait_for_mysql() {
    echo "⏳ Waiting for MySQL to be ready..."
    while ! mysqladmin ping -h"${DB_HOST:-mysql}" -u"${DB_USERNAME:-photomatch}" -p"${DB_PASSWORD:-secret}" --silent 2>/dev/null; do
        sleep 2
    done
    echo "✅ MySQL is ready!"
}

# Check if Laravel is installed
if [ ! -f "artisan" ]; then
    echo "📦 Laravel not found. Installing Laravel 11..."

    # Create temporary project
    composer create-project --prefer-dist laravel/laravel:^11.0 /tmp/laravel --no-interaction

    # Move files to working directory
    shopt -s dotglob
    mv /tmp/laravel/* /var/www/html/
    shopt -u dotglob
    rm -rf /tmp/laravel

    echo "✅ Laravel 11 installed!"
fi

# Install Composer dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# Copy .env if not exists
if [ ! -f ".env" ]; then
    echo "📄 Creating .env file..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
    fi
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:placeholder" ]; then
    if grep -q "APP_KEY=$" .env 2>/dev/null || grep -q "APP_KEY=base64:placeholder" .env 2>/dev/null; then
        echo "🔑 Generating APP_KEY..."
        php artisan key:generate --force
    fi
fi

# Wait for MySQL before running migrations
wait_for_mysql

# Check if Laravel Breeze is installed
if ! grep -q "laravel/breeze" composer.json 2>/dev/null; then
    echo "🎨 Installing Laravel Breeze with Blade..."
    composer require laravel/breeze --dev --no-interaction
    php artisan breeze:install blade --no-interaction
    echo "✅ Laravel Breeze installed!"
fi

# Install npm dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "📦 Installing npm dependencies..."
    npm install
fi

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Create UserSeeder if it doesn't exist
if [ ! -f "database/seeders/UserSeeder.php" ]; then
    echo "🌱 Creating UserSeeder..."
    cat > database/seeders/UserSeeder.php << 'SEEDER'
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@photomatch.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Client',
                'email' => 'client@photomatch.test',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Photographe',
                'email' => 'photographe@photomatch.test',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
SEEDER
fi

# Run seeders
echo "🌱 Running seeders..."
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true

# Create storage link
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

# Build assets
echo "🏗️ Building assets..."
npm run build

# Clear and cache configuration
echo "🧹 Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "========================================"
echo "  ✅ PhotoMatch is ready!"
echo "========================================"
echo "  🌐 Application: http://localhost:8080"
echo "  📧 Mailpit: http://localhost:8025"
echo "  🗄️ phpMyAdmin: http://localhost:8081"
echo "========================================"
echo ""
echo "  Test accounts:"
echo "  - admin@photomatch.test / password"
echo "  - client@photomatch.test / password"
echo "  - photographe@photomatch.test / password"
echo "========================================"

# Execute the main command
exec "$@"
