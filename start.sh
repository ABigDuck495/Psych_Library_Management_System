#!/bin/sh
set -e

php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM in background
php-fpm &

# Start Nginx in foreground
nginx -g 'daemon off;'