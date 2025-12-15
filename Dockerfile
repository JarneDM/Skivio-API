FROM php:8.1-fpm

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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --no-progress

EXPOSE 9000

CMD ["php-fpm"]