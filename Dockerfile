# Shared PHP platform for dependency installation and production.
FROM php:8.3-fpm-bookworm AS php-base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libonig-dev libcurl4-openssl-dev libxml2-dev libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql pdo_sqlite mbstring zip bcmath gd curl \
        dom simplexml xml xmlreader xmlwriter opcache \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

FROM php-base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --no-interaction --prefer-dist --no-progress \
    --no-scripts --no-autoloader

COPY . .

# Package discovery boots Laravel; use an ephemeral DB only for this command.
RUN mkdir -p bootstrap/cache storage/framework/cache/data \
        storage/framework/sessions storage/framework/views storage/logs \
    && DB_CONNECTION=sqlite DB_DATABASE=:memory: DATABASE_URL= \
        composer dump-autoload --no-dev --optimize --no-interaction \
    && composer check-platform-reqs --no-dev

FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

FROM php-base AS runtime

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

ENV APP_ENV=production \
    APP_DEBUG=false

COPY --from=vendor /var/www /var/www
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p public/uploads \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads

USER www-data

# Serve public/ through an Nginx service.
EXPOSE 9000

CMD ["php-fpm"]

FROM nginxinc/nginx-unprivileged:stable-alpine AS web

WORKDIR /var/www

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --from=vendor /var/www/public ./public

COPY --from=frontend /app/public/build ./public/build

USER nginx

EXPOSE 8080
