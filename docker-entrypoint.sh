#!/bin/sh
set -e # Exit immediately if a command exits with a non-zero status.

# cd to /var/www if not already there (though WORKDIR should handle this)
cd /var/www

# If .env doesn't exist, copy .env.example
if [ ! -f ".env" ]; then
  echo "Creating .env file from .env.example"
  cp .env.example .env
  echo "Generating APP_KEY..."
  php artisan key:generate --force
else
  # Check if APP_KEY is empty in existing .env and generate if needed
  APP_KEY_VALUE=$(grep '^APP_KEY=' .env | cut -d '=' -f2-)
  if [ -z "$APP_KEY_VALUE" ]; then
    echo "APP_KEY is empty in existing .env. Generating APP_KEY..."
    php artisan key:generate --force
  fi
fi

if [ "$SKIP_MIGRATIONS" != "true" ]; then
  echo "Running database migrations..."
  php artisan migrate --force
else
  echo "SKIP_MIGRATIONS is set to true, skipping migrations."
fi

echo "Caching Laravel configurations..."
php artisan config:cache
echo "Caching Laravel routes..."
php artisan route:cache

echo "Starting PHP-FPM..."
# Execute the command passed to the entrypoint (which is php-fpm by default from the CMD in Dockerfile)
exec "$@"
