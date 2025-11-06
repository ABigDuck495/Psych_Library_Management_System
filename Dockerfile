FROM alpine:latest

# Install only what we need
RUN apk update && apk add --no-cache \
    nginx \
    php83 \
    php83-fpm \
    php83-common \
    php83-pdo \
    php83-mbstring \
    php83-xml \
    php83-json \
    php83-curl \
    php83-tokenizer \
    php83-fileinfo \
    php83-openssl \
    php83-iconv \
    curl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create directories
RUN mkdir -p /var/www/html /run/nginx

# Create proper PHP-FPM config without escape issues
RUN printf "[global]\ndaemonize = no\nerror_log = /dev/stderr\n\n[www]\nuser = nobody\ngroup = nobody\nlisten = 127.0.0.1:9000\npm = dynamic\npm.max_children = 5\npm.start_servers = 2\npm.min_spare_servers = 1\npm.max_spare_servers = 3\n" > /etc/php83/php-fpm.d/www.conf

# Create proper Nginx config without escape issues
RUN printf "error_log /dev/stderr warn;\nevents {\n    worker_connections 1024;\n}\nhttp {\n    server {\n        listen \${PORT:-8000};\n        root /var/www/html/public;\n        index index.php;\n        location / {\n            try_files \$uri \$uri/ /index.php?\$query_string;\n        }\n        location ~ \.php\$ {\n            fastcgi_pass 127.0.0.1:9000;\n            fastcgi_index index.php;\n            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n            include fastcgi_params;\n        }\n    }\n}\n" > /etc/nginx/nginx.conf

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create test file
RUN echo "<?php echo 'Hello World'; ?>" > /var/www/html/public/index.php

# Set permissions
RUN chown -R nobody:nobody /var/www/html

EXPOSE 8000

# Simple start command
CMD ["sh", "-c", "php-fpm83 -D && nginx -g 'daemon off;'"]