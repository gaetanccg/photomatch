# =============================================================================
# Dockerfile de Production - Trouve Ton Photographe
# Compatible Render, Railway, et autres PaaS
# =============================================================================

FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    postgresql-dev \
    nginx \
    supervisor \
    bash \
    netcat-openbsd

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =============================================================================
# Build stage - Install dependencies and build assets
# =============================================================================
FROM base AS build

# Install Node.js for building assets
RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# Copy composer files first (better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev)
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies and build
RUN npm ci

# Copy application code
COPY . .

# Run composer scripts that need the full codebase
RUN composer run-script post-autoload-dump

# Build frontend assets
RUN npm run build

# Remove node_modules (not needed in production)
RUN rm -rf node_modules

# =============================================================================
# Production stage - Final image
# =============================================================================
FROM base AS production

WORKDIR /var/www/html

# Copy built application from build stage
COPY --from=build /var/www/html /var/www/html

# Copy production configs
COPY docker/production/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/production/php.ini /usr/local/etc/php/conf.d/production.ini
COPY docker/production/supervisord.conf /etc/supervisord.conf
COPY docker/production/start.sh /usr/local/bin/start.sh

# Make start script executable
RUN chmod +x /usr/local/bin/start.sh

# Create necessary directories and set permissions
RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Railway uses PORT env variable)
EXPOSE ${PORT:-80}

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD curl -f http://localhost:${PORT:-80}/up || exit 1

# Start application
CMD ["/usr/local/bin/start.sh"]
