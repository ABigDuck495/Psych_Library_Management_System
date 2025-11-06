#!/bin/sh
set -e

# Create necessary directories
mkdir -p /etc/nginx /var/log/nginx /var/run/php

# Copy nginx config to correct location
cp nginx.conf /etc/nginx/nginx.conf

php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM with default config (no custom config needed)
php-fpm -D

# Start Nginx with our custom config
nginx -g 'daemon off;'