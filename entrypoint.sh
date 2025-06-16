#!/bin/sh

echo "Esperando a que la base de datos esté lista en $DB_HOST:$DB_PORT..."

while ! nc -z mysql.railway.internal 3306; do
  echo "Base de datos no disponible, esperando..."
  sleep 2
done

echo "Base de datos disponible, ejecutando migraciones..."

php artisan migrate:fresh --force
php artisan db:seed --force

echo "Iniciando servidor..."

php artisan serve --host=0.0.0.0 --port=8080
