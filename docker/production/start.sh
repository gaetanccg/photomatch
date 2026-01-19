#!/bin/bash
set -e

echo "========================================"
echo "  PhotoMatch - Production Startup"
echo "========================================"

# Wait for database to be ready
echo "Waiting for database connection..."
max_attempts=30
attempt=0
until php artisan db:monitor --databases=mysql 2>/dev/null || [ $attempt -ge $max_attempts ]; do
    attempt=$((attempt + 1))
    echo "Attempt $attempt/$max_attempts - Database not ready, waiting..."
    sleep 2
done

if [ $attempt -ge $max_attempts ]; then
    echo "Warning: Could not verify database connection, proceeding anyway..."
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configuration for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
echo "Creating storage link..."
php artisan storage:link 2>/dev/null || true

# Ensure correct permissions
echo "Setting permissions..."
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "========================================"
echo "  Starting PHP-FPM and Nginx..."
echo "========================================"

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
