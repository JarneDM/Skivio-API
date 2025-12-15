FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 9000

CMD ["php-fpm"]