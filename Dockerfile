FROM php:8.2-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia tu app
COPY . /var/www

WORKDIR /var/www

# Permisos
RUN chown -R www-data:www-data /var/www

# Puerto expuesto
EXPOSE 8080

# Comando por defecto
CMD php artisan serve --host=0.0.0.0 --port=8080

RUN composer install --no-dev --optimize-autoloader
