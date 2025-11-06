#!/bin/sh
set -e

echo "Preparing environment..."

# Create necessary directories
mkdir -p /etc/nginx /var/log/nginx /var/run/php

# Copy nginx config to correct location
cp nginx.conf /etc/nginx/nginx.conf

echo "Running Laravel setup..."
php artisan migrate --force
php artisan db:seed --force

echo "PHP-FPM config:"
cat /app/php-fpm.conf

echo "Checking php-fpm binary..."
which php-fpm || echo "php-fpm not found"
php-fpm -v || echo "php-fpm failed to run"

echo "Starting PHP-FPM..."
php-fpm -y /app/php-fpm.conf &

# Wait briefly to allow PHP-FPM to bind
sleep 2

echo "Checking PHP-FPM port binding..."
ss -an | grep 9000 && echo "PHP-FPM is listening on port 9000" || echo "PHP-FPM is NOT listening on port 9000"

echo "Starting Nginx..."
exec nginx -c /app/nginx.conf -g 'daemon off;'