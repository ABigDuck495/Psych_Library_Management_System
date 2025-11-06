#!/bin/sh
set -e

# Create necessary directories
mkdir -p /etc/nginx /var/log/nginx /var/run/php

# Copy nginx config to correct location
cp nginx.conf /etc/nginx/nginx.conf

php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM with default config
php-fpm -y /app/php-fpm.conf &

#debug
echo "Checking PHP-FPM port..."
netstat -an | grep 9000 || echo "PHP-FPM is NOT listening on port 9000"

# Start Nginx with our custom config
nginx -c /app/nginx.conf -g 'daemon off;'