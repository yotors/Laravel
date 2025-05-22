#!/bin/sh
set -e # Exit immediately if a command exits with a non-zero status.

# cd to /var/www if not already there (though WORKDIR should handle this)
cd /var/www

# If .env doesn't exist, copy .env.example
if [ ! -f ".env" ]; then
  echo "Creating .env file from .env.example"
  cp .env.example .env
  # Generate app key only if we just created the .env file
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

# Optional: Check if APP_KEY is empty in existing .env and generate if needed
if [ -f ".env" ]; then
  # Read APP_KEY from .env file
  APP_KEY_VALUE=$(grep '^APP_KEY=' .env | cut -d '=' -f2-)
  if [ -z "$APP_KEY_VALUE" ]; then
    echo "APP_KEY is empty. Generating APP_KEY..."
    php artisan key:generate --force
  fi
fi


echo "Running database migrations..."
php artisan migrate --force

# # Optional: Clear and cache configuration (can be beneficial in production)
# # Consider if this should be run here or as part of a build/deploy script
# # php artisan config:cache
# # php artisan route:cache
# # php artisan view:cache

echo "Starting PHP-FPM..."
# Execute the command passed to the entrypoint (which is php-fpm by default from the CMD in Dockerfile)
exec "$@"
