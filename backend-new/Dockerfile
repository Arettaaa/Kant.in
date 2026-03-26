FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip curl libssl-dev pkg-config \
    && docker-php-ext-install zip

# Install extension MongoDB asli
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install package laravel
RUN composer install --no-interaction --optimize-autoloader

# Jalankan server di port 8080 (standar Railway Docker)
CMD php artisan serve --host=0.0.0.0 --port=8080