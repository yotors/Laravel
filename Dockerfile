FROM php:8.2-fpm
ARG user=defaultuser
ARG uid=1000
ARG INSTALL_DEV_DEPENDENCIES=false
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    # Added libraries for PHP extensions:
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpq-dev \
    default-libmysqlclient-dev
RUN apt clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN echo "User: $user, UID: $uid"

# Create user
RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user}
RUN mkdir -p /home/${user}/.composer && \
    chown -R ${user}:${user} /home/${user}

# Set working directory
WORKDIR /var/www

# Copy composer files and install dependencies as root
COPY composer.json composer.lock ./
RUN if [ "$INSTALL_DEV_DEPENDENCIES" = "true" ]; then \
              echo "Installing ALL Composer dependencies (including dev)..."; \
              composer install --no-scripts --optimize-autoloader; \
            else \
              echo "Installing Composer dependencies WITHOUT dev dependencies..."; \
              composer install --no-dev --no-scripts --optimize-autoloader; \
            fi

# Copy the rest of the application files
COPY . .

# Change ownership of all application files to the non-root user
RUN chown -R ${user}:${user} /var/www

# Set permissions for Laravel
# Ensure these directories exist and are writable by the application user
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chown -R ${user}:${user} storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy and set up entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch to non-root user
USER ${user}

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"] # Default command for the entrypoint
