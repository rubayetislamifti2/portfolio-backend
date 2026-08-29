# ---- Stage 1: Build frontend assets (Vite) ----
FROM node:20-alpine AS node-build

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- Stage 2: Install PHP dependencies ----
FROM composer:2 AS composer-build

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---- Stage 3: Final runtime image ----
FROM php:8.5-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    bash \
    icu-dev \
    oniguruma-dev

# PHP extensions (Mise uses PostgreSQL)
RUN docker-php-ext-install pdo_pgsql pgsql

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
 && docker-php-ext-install gd

RUN docker-php-ext-install zip

#RUN docker-php-ext-install opcache

RUN docker-php-ext-install bcmath

RUN docker-php-ext-install intl

RUN docker-php-ext-install mbstring

RUN docker-php-ext-install exif

RUN docker-php-ext-install pcntl

WORKDIR /var/www/html

# Copy built app (with vendor + compiled frontend assets) from previous stages
COPY --from=composer-build /app /var/www/html
COPY --from=node-build /app/public/build /var/www/html/public/build

# Config files
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && mkdir -p /run/nginx

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
