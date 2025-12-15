FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip \
  libpq-dev \
  libonig-dev \
  libxml2-dev \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
  pdo \
  pdo_mysql \
  mbstring \
  exif \
  pcntl \
  bcmath \
  xml

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer self-update --2 && composer clear-cache

WORKDIR /var/www/html

COPY . .

# why it says no changes??? this comment is to push 
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/c\<Directory /var/www/html/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' /etc/apache2/apache2.conf

RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --prefer-dist --no-scripts -vv || (cat composer.lock 2>/dev/null || echo "Install failed")

RUN cp .env.example .env || true
RUN COMPOSER_MEMORY_LIMIT=-1 php artisan key:generate --force || true

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["apache2-foreground"]