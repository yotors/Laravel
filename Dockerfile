# syntax=docker/dockerfile:1

# ... (your existing comments)

################################################################################

# Create a stage for installing app dependencies defined in Composer.
FROM composer:lts as deps

WORKDIR /app
# Copy all project files into this stage
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY artisan /usr/local/bin/
RUN chmod +x /usr/local/bin/artisan 
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist

RUN php artisan package:discover --ansi
################################################################################

# Create a new stage for running the application that contains the minimal
# runtime dependencies for the application. This often uses a different base
# image from the install or build stage where the necessary files are copied
# from the install stage.
#
# The example below uses the PHP Apache image as the foundation for running the app.
# By specifying the "y-apache" tag, it will also use whatever happens to be the
# most recent version of that tag when you build your Dockerfile.
# If reproducability is important, consider using a specific digest SHA, like
# php@sha256:99cede493dfd88720b610eb8077c8688d3cca50003d76d1d539b0efc8cca72b4.
FROM php:8.2-apache as final

# Copy the dependencies from the previous stage
COPY --from=deps /app/vendor /var/www/html/vendor
# Copy your entire Laravel project
COPY . /var/www/html/

# Install Apache modules
# Configure Apache
COPY .htaccess /var/www/html/

WORKDIR /var/www/html

# Expose port 80
EXPOSE 80

# Enable rewrite module
RUN a2enmod rewrite && service apache2 restart 