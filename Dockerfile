FROM php:8.2-fpm

# Instala dependencias necesarias, incluyendo netcat-openbsd
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev netcat-openbsd \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia código de la app
COPY . /var/www
WORKDIR /var/www

# Establece permisos correctos
RUN chown -R www-data:www-data /var/www

# Instala dependencias de producción
RUN composer install --optimize-autoloader

# Copia el entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expone el puerto usado por Laravel
EXPOSE 8080

# Usa el script como punto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

