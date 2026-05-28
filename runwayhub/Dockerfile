# RunwayHub Docker Build
FROM php:8.2-apache

# Set working directory
WORKDIR /app

# Install extensions
RUN docker-php-ext-install pdo_sqlite mbstring xml && \
    pecl install redis && \
    docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Configure Apache
COPY config/apache2/vhosts.conf /etc/apache2/sites-enabled/000-default.conf
COPY config/apache2/php.ini /etc/php/8.2/apache2/php.ini
COPY config/apache2/.htaccess /app/.htaccess

# Create directories
RUN mkdir -p /app/logs /app/uploads

# Expose port
EXPOSE ${APP_PORT:-8080}

# Start Apache
CMD ["apache2-foreground"]
