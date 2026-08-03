FROM php:8.2-apache

# Install dependencies and extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    ca-certificates \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite

# Update CA certificates
RUN update-ca-certificates

# Copy the application source code to the Apache root
COPY . /var/www/html/

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
