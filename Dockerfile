FROM alpine:latest

# Install system dependencies including Composer and missing extensions
RUN apk update && apk add --no-cache \
    nginx \
    php83 \
    php83-fpm \
    php83-common \
    php83-pdo \
    php83-pdo_mysql \
    php83-mysqli \
    php83-opcache \
    php83-zip \
    php83-gd \
    php83-sodium \
    php83-mbstring \
    php83-xml \
    php83-json \
    php83-curl \
    php83-tokenizer \
    php83-phar \
    php83-fileinfo \
    php83-simplexml \
    php83-xmlreader \
    php83-xmlwriter \
    php83-dom \
    php83-ctype \
    php83-session \
    php83-bcmath \
    php83-intl \
    php83-openssl \
    php83-iconv \  # ← ADDED THIS LINE
    supervisor \
    curl \
    git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create necessary directories
RUN mkdir -p /var/www/html \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/log/php \
    && mkdir -p /run/nginx \
    && mkdir -p /etc/nginx/conf.d \
    && mkdir -p /var/run/php

# Create Nginx config file for Railway
RUN echo "server { \
    listen \${PORT:-8000} default_server; \
    root /var/www/html/public; \
    index index.php index.html; \
    client_max_body_size 100M; \
    \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
        include fastcgi_params; \
        fastcgi_read_timeout 300; \
    } \
    \
    location ~ /\.ht { \
        deny all; \
    } \
}" > /etc/nginx/conf.d/default.conf

# Create PHP-FPM config
RUN echo "[global] \
daemonize = no \
\
[www] \
user = nobody \
group = nobody \
listen = 127.0.0.1:9000 \
listen.owner = nobody \
listen.group = nobody \
pm = dynamic \
pm.max_children = 5 \
pm.start_servers = 2 \
pm.min_spare_servers = 1 \
pm.max_spare_servers = 3 \
php_admin_value[memory_limit] = 512M \
php_admin_value[upload_max_filesize] = 100M \
php_admin_value[post_max_size] = 100M \
php_admin_value[max_execution_time] = 300 \
php_admin_value[max_input_time] = 300" > /etc/php83/php-fpm.d/www.conf

# Create supervisord config
RUN echo "[supervisord] \
nodaemon=true \
\
[program:nginx] \
command=nginx -g 'daemon off;' \
autostart=true \
autorestart=true \
stdout_logfile=/dev/stdout \
stdout_logfile_maxbytes=0 \
stderr_logfile=/dev/stderr \
stderr_logfile_maxbytes=0 \
\
[program:php-fpm] \
command=php-fpm83 -F \
autostart=true \
autorestart=true \
stdout_logfile=/dev/stdout \
stdout_logfile_maxbytes=0 \
stderr_logfile=/dev/stderr \
stderr_logfile_maxbytes=0" > /etc/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install Composer dependencies and run Laravel optimizations
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Set permissions
RUN chown -R nobody:nobody /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod +x artisan

# Expose port
EXPOSE 8000

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]