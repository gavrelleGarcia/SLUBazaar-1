FROM php:8.2-apache

# Set the document root to the public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache configuration to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache rewrite module for .htaccess files
RUN a2enmod rewrite

# Install PHP extensions
# XAMPP projects often use mysqli or pdo_mysql
RUN docker-php-ext-install mysqli

# Copy the application code
COPY . /var/www/html/

# Use production database configuration
COPY config/database.production.php config/database.php

# Set correct permissions

RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
