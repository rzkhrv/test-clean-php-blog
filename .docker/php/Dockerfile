FROM composer:latest AS composer-stage
FROM node:latest AS node-stage

FROM php:8.5-fpm

COPY --from=composer-stage /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y unzip && rm -rf /var/lib/apt/lists/*  \
    && docker-php-ext-install pdo_mysql mysqli

COPY . .
COPY --from=node-stage /usr/local/bin/node /usr/local/bin/node
COPY --from=node-stage /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN npm install -g sass
RUN composer install