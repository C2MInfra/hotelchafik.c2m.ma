# Use the official PHP Apache 7.4 image
FROM php:7.4-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files to the container
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
