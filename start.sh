#!/bin/sh
set -e

# Copy nginx config to the correct location
cp nginx.conf /nginx.conf

php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM in background
php-fpm &

# Start Nginx with our custom config
nginx -g 'daemon off;'