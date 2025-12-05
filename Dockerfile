# ---------- Stage 1: Install PHP dependencies ----------
FROM composer:2 AS vendor

WORKDIR /app

# Only copy composer files at first to use Docker cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress

# ---------- Stage 2: PHP + Apache image ----------
FROM php:8.2-apache

# Install system packages and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copy entire Laravel project
COPY . /var/www/html

# Copy vendor from build stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Set the Apache document root to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render exposes port 10000
EXPOSE 10000

# Make Apache listen on Render's PORT
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf && \
    sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
