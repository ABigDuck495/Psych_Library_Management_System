#!/bin/sh
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Running database seeds..."
php artisan db:seed --force

echo "Starting PHP-FPM..."
php-fpm83 -D

echo "Starting Nginx..."
exec nginx -g 'daemon off;'