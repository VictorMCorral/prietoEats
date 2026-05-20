#!/bin/sh
set -e

echo "=== PrietoEats - Initialization Script ==="

# Crear directorios necesarios
mkdir -p /app/bootstrap/cache
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/app/public/img
chmod -R 777 /app/bootstrap/cache /app/storage

echo "Running database migrations..."
php artisan migrate --force || true

echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

echo "Creating storage link..."
php artisan storage:link || true

echo "=== Setup complete! Starting services... ==="

# The base image entrypoint expects a command argument.
if [ "$#" -eq 0 ]; then
	set -- supervisord
fi

# Call the original webdevops entrypoint which manages supervisord
# Use the original entrypoint path provided by the base image
exec /opt/docker/bin/entrypoint.sh "$@"
