FROM composer:latest AS composer-stage

FROM php:8.5-fpm

COPY --from=composer-stage /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql mysqli

COPY . .

RUN composer install