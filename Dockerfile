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

# Create Nginx config
RUN echo "error_log /dev/stderr warn;\n\
events {\n\
    worker_connections 1024;\n\
}\n\
http {\n\
    include /etc/nginx/mime.types;\n\
    default_type application/octet-stream;\n\
    access_log /dev/stdout;\n\
    \n\
    server {\n\
        listen \${PORT:-8000} default_server;\n\
        root /var/www/html/public;\n\
        index index.php index.html;\n\
        \n\
        # Health check endpoint\n\
        location /health {\n\
            return 200 'OK';\n\
            add_header Content-Type text/plain;\n\
        }\n\
        \n\
        location / {\n\
            try_files \$uri \$uri/ /index.php?\$query_string;\n\
        }\n\
        \n\
        location ~ \.php$ {\n\
            fastcgi_pass 127.0.0.1:9000;\n\
            fastcgi_index index.php;\n\
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n\
            include fastcgi_params;\n\
            \n\
            # Timeouts\n\
            fastcgi_read_timeout 300;\n\
            fastcgi_connect_timeout 300;\n\
            fastcgi_send_timeout 300;\n\
        }\n\
        \n\
        location ~ /\.ht {\n\
            deny all;\n\
        }\n\
    }\n\
}" > /etc/nginx/nginx.conf

# Create PHP-FPM config
RUN echo "[global]\n\
daemonize = no\n\
error_log = /dev/stderr\n\
\n\
[www]\n\
user = nobody\n\
group = nobody\n\
listen = 127.0.0.1:9000\n\
listen.owner = nobody\n\
listen.group = nobody\n\
pm = dynamic\n\
pm.max_children = 5\n\
pm.start_servers = 2\n\
pm.min_spare_servers = 1\n\
pm.max_spare_servers = 3\n\
\n\
; Log everything for debugging\n\
catch_workers_output = yes\n\
php_flag[display_errors] = on\n\
php_value[error_log] = /dev/stderr\n\
php_flag[log_errors] = on\n\
\n\
; Increase memory and execution time\n\
php_admin_value[memory_limit] = 512M\n\
php_admin_value[max_execution_time] = 300\n\
php_admin_value[max_input_time] = 300" > /etc/php83/php-fpm.d/www.conf

WORKDIR /var/www/html
COPY . .

# Install dependencies ONLY
RUN composer install --no-dev --optimize-autoloader

# Create a simple test PHP file
RUN echo "<?php\n\
header('Content-Type: text/plain');\n\
echo 'PHP is working!\\n';\n\
echo 'PHP Version: ' . PHP_VERSION . '\\n';\n\
echo 'Server: ' . (\$_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . '\\n';\n\
?>" > /var/www/html/public/test.php

# Create health check file
RUN echo "<?php\n\
http_response_code(200);\n\
echo 'OK';\n\
?>" > /var/www/html/public/health.php

# Create index.php if it doesn't exist
RUN if [ ! -f /var/www/html/public/index.php ]; then \
        echo "<?php\n\
require __DIR__.'/../vendor/autoload.php';\n\
\$app = require_once __DIR__.'/../bootstrap/app.php';\n\
\$kernel = \$app->make(Illuminate\\Contracts\\Http\\Kernel::class);\n\
\$response = \$kernel->handle(\n\
    \$request = Illuminate\\Http\\Request::capture()\n\
);\n\
\$response->send();\n\
\$kernel->terminate(\$request, \$response);\n\
?>" > /var/www/html/public/index.php; \
    fi

# Set permissions
RUN chown -R nobody:nobody /var/www/html && \
    chmod -R 755 /var/www/html && \
    chown -R nobody:nobody /var/log/nginx && \
    chown -R nobody:nobody /run/nginx

EXPOSE 8000

# Start both services directly (no supervisor)
CMD ["sh", "-c", "php-fpm83 -D && nginx -g 'daemon off;'"]