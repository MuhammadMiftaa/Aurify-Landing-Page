# ── Stage 1: Build frontend assets ──
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build


# ── Stage 2: Install PHP dependencies ──
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs


# ── Stage 3: Production image ──
FROM php:8.3-fpm-alpine AS production

# Install system deps + PHP extensions
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        icu-dev \
        oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        intl \
        opcache \
    && rm -rf /var/cache/apk/*

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php.ini "$PHP_INI_DIR/conf.d/99-aurify.ini"
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

# Copy application
COPY --chown=www-data:www-data . .

# Copy built assets from previous stages
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

# Create required directories
RUN mkdir -p storage/framework/{cache,sessions,views,testing} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Remove dev files
RUN rm -rf node_modules tests .git \
    resources/js resources/css \
    vite.config.js package.json package-lock.json \
    phpunit.xml .editorconfig .env

EXPOSE 8000

ENTRYPOINT ["sh", "/var/www/html/docker/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
