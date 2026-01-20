#!/bin/bash
set -e

echo "========================================"
echo "  Trouve Ton Photographe - Production"
echo "========================================"
echo ""

# Railway provides PORT env variable, default to 80
export PORT="${PORT:-80}"
echo "Server will listen on port: $PORT"

# Update nginx to listen on the correct port
sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf

# Wait for database to be ready (with timeout)
echo ""
echo "Checking database connection..."
max_attempts=30
attempt=0

# Check if we have database credentials
if [ -n "$MYSQL_HOST" ] || [ -n "$DB_HOST" ]; then
    DB_HOST_CHECK="${MYSQL_HOST:-$DB_HOST}"
    DB_PORT_CHECK="${MYSQL_PORT:-${DB_PORT:-3306}}"

    until nc -z "$DB_HOST_CHECK" "$DB_PORT_CHECK" 2>/dev/null || [ $attempt -ge $max_attempts ]; do
        attempt=$((attempt + 1))
        echo "  Attempt $attempt/$max_attempts - Waiting for database at $DB_HOST_CHECK:$DB_PORT_CHECK..."
        sleep 2
    done

    if [ $attempt -ge $max_attempts ]; then
        echo "  Warning: Could not connect to database after $max_attempts attempts"
        echo "  Proceeding anyway - Laravel will handle connection errors"
    else
        echo "  Database is ready!"
    fi
else
    echo "  No database host configured, skipping connection check"
fi

# Run migrations (--force required in production)
echo ""
echo "Running database migrations..."
php artisan migrate --force || {
    echo "  Warning: Migration failed, check database connection"
}

# Cache configuration for better performance
echo ""
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link if not exists
echo ""
echo "Setting up storage..."
php artisan storage:link 2>/dev/null || true

# Ensure correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo ""
echo "========================================"
echo "  Starting PHP-FPM and Nginx on port $PORT"
echo "========================================"
echo ""

# Start supervisord (manages both nginx and php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
