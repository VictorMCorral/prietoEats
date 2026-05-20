#!/bin/sh

echo "=== PrietoEats - Initialization Script ==="
echo "PWD: $(pwd)"
echo "User: $(whoami)"

# Crear directorios necesarios
echo "Creating directories..."
mkdir -p /app/bootstrap/cache
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/app/public/img
chmod -R 777 /app/bootstrap/cache /app/storage
echo "Directories created and permissions set"

echo "Installing PHP dependencies..."
cd /app && composer install --no-interaction --prefer-dist 2>&1 | tail -5
echo "Composer install completed"

echo "Running database migrations..."
php /app/artisan migrate --force 2>&1 | tail -3 || echo "Migration completed or failed gracefully"

echo "Clearing caches..."
php /app/artisan config:clear 2>&1 || true
php /app/artisan cache:clear 2>&1 || true
php /app/artisan view:clear 2>&1 || true
echo "Caches cleared"

echo "Creating storage link..."
php /app/artisan storage:link 2>&1 || true
echo "Storage link created"

echo "=== Setup complete! Starting services... ==="
ls -la /app/vendor/autoload.php 2>&1 || echo "WARNING: vendor/autoload.php not found!"

# The base image entrypoint expects a command argument.
if [ "$#" -eq 0 ]; then
	set -- supervisord
fi

# Call the original webdevops entrypoint which manages supervisord
echo "Calling original entrypoint with args: $@"
exec /opt/docker/bin/entrypoint.sh "$@"
