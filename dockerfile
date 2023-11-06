# Use the official PHP 8.2 image as the base image
FROM php:8.2-fpm

# Install the PHP PDO extension, zip extension, unzip, and git
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    unzip \
    docker-php-ext-install pdo_mysql zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Make Composer executable
RUN chmod +x /usr/local/bin/composer

# Copy the Laravel application into the container
COPY . /var/www

# Set the working directory
WORKDIR /var/www

# Change the ownership of the /var/www directory to www-data
RUN chown -R www-data:www-data /var/www

# Switch to the www-data user
USER www-data

# Install the Laravel dependencies
RUN composer install

# Expose port 9000 for the PHP FPM server
EXPOSE 9000
