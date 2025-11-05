# Use a stable PHP-FPM base image for production web serving
FROM php:8.3-fpm-alpine

# Set the working directory for the application code
WORKDIR /var/www/html

# --- Build Dependencies and PHP Extensions ---

# Step 1: Install OS packages and necessary build dependencies
RUN apk update && apk add --no-cache \
    nginx \
    mysql-client \
    \
    # Install build dependencies for PHP extensions, creating a temporary group
    && apk add --no-cache --virtual .build-deps \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libsodium-dev

# Step 2: Install PHP extensions and clean up build dependencies
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql opcache zip gd sodium \
    \
    # Fix for Composer (CLI): explicitly enable all required non-core extensions
    && docker-php-ext-enable \
        pdo_mysql \
        opcache \
        zip \
        gd \
        sodium \
    \
    # Cleanup: Remove the temporary build dependencies to keep the image small
    && apk del .build-deps

# --- Application Setup ---

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy ALL files from the repository root into the container's working directory
# This single command ensures composer.json/lock and all other files are available
COPY . .

# Run composer install to install dependencies
# We run this AFTER COPY . . to ensure the files are present in the build context
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# --- NGINX Configuration ---

# Create a simple Nginx config file that passes PHP requests to PHP-FPM
RUN echo "server { \
    listen 8000 default_server; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    location ~ \.php$ { \
        # Pass the PHP scripts to PHP-FPM running on 9000
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}" > /etc/nginx/conf.d/default.conf

# --- Startup Command ---

# Expose the port NGINX is listening on
EXPOSE 8000

# Start PHP-FPM and Nginx simultaneously (required to keep the container alive)
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"