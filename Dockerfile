FROM php:8.2-apache

ENV DEBIAN_FRONTEND noninteractive


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
    && rm -rf /var/lib/apt/lists/*

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

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer self-update --2 && composer clear-cache

WORKDIR /var/www/html

# Install PHP dependencies first to ensure vendor exists
COPY composer.json composer.lock ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --prefer-dist --no-scripts --no-dev --optimize-autoloader -vv

# Then copy the rest of the application code
COPY . .


COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY docker/start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

RUN cp .env.example .env || true
RUN COMPOSER_MEMORY_LIMIT=-1 php artisan key:generate --force || true

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]