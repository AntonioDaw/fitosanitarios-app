#!/bin/sh

# Verificar que las variables necesarias estén definidas
if [ -z "$DB_HOST" ] || [ -z "$DB_PORT" ]; then
  echo "❌ Las variables DB_HOST y/o DB_PORT no están definidas. Abortando."
  exit 1
fi

echo "⏳ Esperando a que la base de datos esté lista en $DB_HOST:$DB_PORT..."

# Esperar hasta que la base de datos esté disponible
while ! nc -z "$DB_HOST" "$DB_PORT"; do
  echo "🚧 Base de datos no disponible, esperando..."
  sleep 2
done

echo "✅ Base de datos disponible, ejecutando migraciones..."

# Ejecutar migraciones y seeders
php artisan migrate --force
php artisan db:seed --force

echo "🚀 Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=8080

