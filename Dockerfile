# Usa la imagen oficial de PHP con las extensiones necesarias
FROM php:8.1-fpm

# Instala dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el composer.lock y composer.json
COPY composer.lock composer.json /var/www/html/

# Instala las dependencias PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Copia el resto de la aplicación
COPY . /var/www/html

# Otorga permisos a la carpeta storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 9000 (usado por php-fpm)
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
