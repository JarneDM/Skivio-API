# Use the official PHP image with Apache on 8.2
FROM php:8.2-apache

# Set environment variable for non-interactive commands
ENV DEBIAN_FRONTEND noninteractive

## 1. Install System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    # Node/NPM for running build scripts (if you have frontend assets)
    nodejs \
    npm \
    # Clean up apt lists
    && rm -rf /var/lib/apt/lists/*

## 2. Install PHP Extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    zip \
    gd

## 3. Configure Apache and Composer
RUN a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2 && composer clear-cache

# Set the working directory for the application
WORKDIR /var/www/html

## 4. Copy and Install Dependencies (The critical fix)
# COPY the full application code *before* composer install, 
# because your composer scripts rely on the 'artisan' command.
COPY . .

# Install PHP dependencies. 
# We remove --no-scripts to allow post-autoload-dump to run successfully.
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader -vv

## 5. Final Configuration and Setup
# Copy Apache VHost configuration
# Ensure your docker/000-default.conf file has DocumentRoot /var/www/html/public
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy and prepare the startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Run application specific setup (key generation)
RUN cp .env.example .env || true
RUN COMPOSER_MEMORY_LIMIT=-1 php artisan key:generate --force || true

# Clear and cache application setup for production performance
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan config:cache
RUN php artisan route:cache

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]