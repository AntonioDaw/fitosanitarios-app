#!/bin/sh
set -e

echo "Esperando a que la base de datos esté lista..."
while ! nc -z $DB_HOST $DB_PORT; do
  echo "Esperando a $DB_HOST:$DB_PORT..."
  sleep 1
done

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Ejecutando seeders..."
php artisan db:seed --force

echo "Arrancando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=8080
