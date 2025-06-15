FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www
WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

RUN composer install --no-dev --optimize-autoloader

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN apt-get update && apt-get install -y netcat

EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]

