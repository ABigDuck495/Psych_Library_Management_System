#!/bin/sh
set -e

echo "Preparing environment..."

# Create necessary directories
mkdir -p /etc/nginx /var/log/nginx /var/run/php

# Copy nginx config to correct location
cp nginx.conf /etc/nginx/nginx.conf

# Ensure Laravel can write logs and cache
echo "Fixing Laravel permissions..."
chmod -R 775 storage bootstrap/cache
chown -R nobody:nogroup storage bootstrap/cache

# Ensure log file exists
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod 664 storage/logs/laravel.log
chown nobody:nogroup storage/logs/laravel.log

# Clear and cache Laravel config
echo "Caching Laravel config..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running Laravel setup..."
php artisan migrate --force
php artisan db:seed --force

echo "PHP-FPM config:"
cat /app/php-fpm.conf

echo "Checking php-fpm binary..."
which php-fpm || echo "php-fpm not found"
php-fpm -v || echo "php-fpm failed to run"

echo "Starting PHP-FPM..."
php-fpm --nodaemonize --fpm-config /app/php-fpm.conf &

sleep 2

echo "Checking PHP-FPM process..."
ps aux | grep php-fpm | grep -v grep && echo "PHP-FPM process is running" || echo "PHP-FPM process not found"

echo "Inspecting Laravel error log..."
tail -n 50 /app/storage/logs/laravel.log || echo "No Laravel log found"

echo "Starting Nginx..."
exec nginx -c /app/nginx.conf -g 'daemon off;'