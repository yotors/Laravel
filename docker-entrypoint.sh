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
  echo "Attempting to run database migrations..."
  
  max_attempts=5
  attempt_num=1
  delay_seconds=15
  migration_success=false

  while [ $attempt_num -le $max_attempts ]; do
    echo "Migration attempt #$attempt_num of $max_attempts..."
    if php artisan migrate --force; then
      migration_success=true
      echo "Migrations successful."
      break
    else
      echo "Migration attempt #$attempt_num failed."
      if [ $attempt_num -lt $max_attempts ]; then
        echo "Waiting $delay_seconds seconds before next attempt..."
        sleep $delay_seconds
      else
        echo "All migration attempts failed."
      fi
    fi
    attempt_num=$((attempt_num + 1))
  done

  if [ "$migration_success" != "true" ]; then
    echo "ERROR: Database migrations failed after $max_attempts attempts. Please check the logs."
    # Optionally, exit 1 here if you want the container to fail loudly if migrations don't pass
    # exit 1 
  fi
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
