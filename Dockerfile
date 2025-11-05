FROM alpine:latest

# Install system dependencies
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
    php83-iconv \
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

# Create Nginx config
RUN echo "server { \
    listen \${PORT:-8000} default_server; \
    root /var/www/html/public; \
    index index.php index.html; \
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

# Create PHP-FPM config
RUN echo "[global]\ndaemonize=no\n[www]\nuser=nobody\ngroup=nobody\nlisten=127.0.0.1:9000\npm=dynamic\npm.max_children=5\npm.start_servers=2\npm.min_spare_servers=1\npm.max_spare_servers=3" > /etc/php83/php-fpm.d/www.conf

# Create supervisord config
RUN echo "[supervisord]\nnodaemon=true\n[program:nginx]\ncommand=nginx -g 'daemon off;'\nautostart=true\nautorestart=true\n[program:php-fpm]\ncommand=php-fpm83 -F\nautostart=true\nautorestart=true" > /etc/supervisord.conf

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R nobody:nobody /var/www/html && chmod -R 755 /var/www/html

EXPOSE 8000

# Simple start command - skip migrations for now
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]