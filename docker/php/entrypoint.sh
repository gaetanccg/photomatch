#!/bin/bash

echo "========================================"
echo "  PhotoMatch - Starting PHP-FPM"
echo "========================================"

# Run migrations if Laravel is installed
if [ -f "artisan" ]; then
    echo "Running migrations..."
    php artisan migrate --force 2>&1 || echo "Migrations skipped or failed"
    php artisan config:clear 2>&1 || true
fi

echo "Starting php-fpm..."

# Execute the main command (php-fpm)
exec "$@"
