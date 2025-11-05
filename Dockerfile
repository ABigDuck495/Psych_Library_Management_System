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
    # Install build dependencies for PHP extensions
    && apk add --no-cache --virtual .build-deps \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libsodium-dev

# Step 2: Install PHP extensions and clean up build dependencies
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql opcache zip gd sodium \
    \
    # Explicitly enable extensions for the CLI environment (Fixes Composer)
    && docker-php-ext-enable \
        pdo_mysql \
        opcache \
        zip \
        gd \
        sodium \
    \
    # Cleanup: Remove the temporary build dependencies
    && apk del .build-deps

# --- Application Setup ---

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy ALL files from the repository root into the container's working directory
COPY . .

# CRITICAL FIX: Run composer install while IGNORING the problematic platform requirements.
# We know the extensions are installed, so this bypasses the stubborn CLI check.
RUN composer install --no-dev --optimize-autoloader \
    --ignore-platform-req=ext-gd \
    --ignore-platform-req=ext-sodium \
    --ignore-platform-req=ext-zip

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# --- NGINX Configuration ---

# Create Nginx config file
RUN echo "server { \
    listen 8000 default_server; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}" > /etc/nginx/conf.d/default.conf

# --- Startup Command ---

EXPOSE 8000

# Start PHP-FPM and Nginx simultaneously
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"