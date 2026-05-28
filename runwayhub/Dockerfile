# Multi-stage Dockerfile for RunwayHub
# Virtual Airline Management System

# Stage 1: Build
FROM php:8.2-cli AS builder

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Copy composer files
WORKDIR /app
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy source code
COPY . .

# Stage 2: Production
FROM php:8.2-fpm

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy from builder
COPY --from=builder /app/vendor /var/www/html/vendor
COPY --from=builder /app/public /var/www/html/public
COPY --from=builder /app/runwayhub /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure PHP-FPM
COPY --from=builder /etc/php/8.2/fpm/php.ini /etc/php/8.2/fpm/php.ini
COPY --from=builder /etc/php/8.2/fpm/conf.d/ /etc/php/8.2/fpm/conf.d/

# Copy custom php-fpm config
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www-fpm-ssl.conf

# Expose ports
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost:9000/ || exit 1

# Command
CMD ["php-fpm"]