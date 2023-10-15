FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        git \
        zip \
        curl \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libonig-dev \
        libpq-dev \
    && docker-php-ext-install -j$(nproc) mbstring pdo pdo_pgsql pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

CMD ["php-fpm"]