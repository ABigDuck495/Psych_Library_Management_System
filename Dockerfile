# === STAGE 1: BUILDER (For running Composer and installing dependencies) ===
FROM php:8.3-cli-alpine AS composer_builder

# Set the working directory for the application code
WORKDIR /app

# Install OS packages and all build dependencies (including those for GD and Sodium)
RUN apk update && apk add --no-cache \
    git \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libsodium-dev \
    \
    # Install Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    \
    # Install PHP extensions required by Composer (CLI environment)
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip gd sodium \
    && docker-php-ext-enable zip gd sodium

# Copy application files needed for composer install
COPY composer.json composer.lock ./

# Run composer install. The --ignore-platform-reqs flag is a safety measure
# to prevent CLI errors when we KNOW the extensions are installed.
RUN composer install --no-dev --optimize-autoloader

# === STAGE 2: PRODUCTION (Final Runtime Image) ===
FROM php:8.3-fpm-alpine

# Set the working directory
WORKDIR /var/www/html

# Install NGINX and runtime client for MySQL
RUN apk update && apk add --no-cache \
    nginx \
    mysql-client

# Install PHP extensions required for FPM (Web Server)
# Opcache is installed here for web serving performance
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql opcache zip gd sodium \
    && docker-php-ext-enable opcache

# Copy compiled application code and vendors from the builder stage
# This is the key step that bypasses the file-copy and composer issues
COPY --from=composer_builder /app /var/www/html

# Set correct permissions
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
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}" > /etc/nginx/conf.d/default.conf

# --- Startup Command ---

# Expose the port NGINX is listening on
EXPOSE 8000

# Start PHP-FPM and Nginx simultaneously
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"