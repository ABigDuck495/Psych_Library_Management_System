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

WORKDIR /var/www/html
COPY . .

# Copy configuration files
COPY nginx.conf /etc/nginx/nginx.conf
COPY php-fpm.conf /etc/php83/php-fpm.d/www.conf

# Install dependencies ONLY
RUN composer install --no-dev --optimize-autoloader

# Create a simple test PHP file
RUN echo "<?php
header('Content-Type: text/plain');
echo 'PHP is working!';
echo 'PHP Version: ' . PHP_VERSION;
?>" > /var/www/html/public/test.php

# Create health check file
RUN echo "<?php
http_response_code(200);
echo 'OK';
?>" > /var/www/html/public/health.php

# Create index.php if it doesn't exist
RUN if [ ! -f /var/www/html/public/index.php ]; then \
        echo "<?php
require __DIR__.'/../vendor/autoload.php';
\$app = require_once __DIR__.'/../bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Http\Kernel::class);
\$response = \$kernel->handle(
    \$request = Illuminate\Http\Request::capture()
);
\$response->send();
\$kernel->terminate(\$request, \$response);
?>" > /var/www/html/public/index.php; \
    fi

# Set permissions
RUN chown -R nobody:nobody /var/www/html && \
    chmod -R 755 /var/www/html && \
    chown -R nobody:nobody /var/log/nginx && \
    chown -R nobody:nobody /run/nginx

EXPOSE 8000

# Start both services directly
CMD ["sh", "-c", "php-fpm83 -D && nginx -g 'daemon off;'"]