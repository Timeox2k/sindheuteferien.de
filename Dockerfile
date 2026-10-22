# Frontend assets (Vite + Tailwind)
FROM node:22.23.4-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Laravel on nginx + PHP-FPM, runs as www-data on port 8080
FROM serversideup/php:8.4-fpm-nginx-v5.0.2
WORKDIR /var/www/html

USER root
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p database/sqlite storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data database/sqlite storage bootstrap/cache
USER www-data
