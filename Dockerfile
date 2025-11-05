# Use a stable PHP-FPM base image (FPM is required for production web serving)
FROM php:8.3-fpm-alpine

# Install core OS utilities and PHP extensions for Laravel (including MySQL and GD)
# Step 1: Install core OS utilities and dependencies
# The '--virtual .build-deps' creates a temporary dependency group for PHP extensions
RUN apk update && apk add --no-cache \
    nginx \
    mysql-client \
    \
    # Install build dependencies required for PHP extensions
    && apk add --no-cache --virtual .build-deps \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev

# Step 2: Configure and install PHP extensions
# NOTE: We remove --with-jpeg, --with-png, and --with-freetype, as they are
# automatically detected on modern Alpine images.
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql opcache zip gd \
    \
    # Cleanup: Remove the temporary build dependencies to keep the image small
    && apk del .build-deps

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files and run install
COPY composer.json composer.lock ./
# --no-dev and --optimize-autoloader for production
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application code
COPY . .

# Set correct permissions using Docker's user system (no 'chmod' needed)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create a temporary Nginx config file that listens on $PORT
RUN echo "server { \
    listen 8000 default_server; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    location ~ \.php$ { \
        # Pass the PHP scripts to PHP-FPM
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}" > /etc/nginx/conf.d/default.conf

# Expose the standard port (Railway will map $PORT to this)
EXPOSE 8000

# Start PHP-FPM and Nginx simultaneously
# This command runs both services in the foreground to prevent the container from exiting
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"