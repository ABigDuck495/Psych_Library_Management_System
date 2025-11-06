#!/bin/sh
set -e

echo "Preparing environment..."

mkdir -p /etc/nginx /var/log/nginx /var/run/php
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
php-fpm --nodaemonize --fpm-config /app/php-fpm.conf &

sleep 2

echo "Checking PHP-FPM process..."
ps aux | grep php-fpm | grep -v grep && echo "PHP-FPM process is running" || echo "PHP-FPM process not found"

echo "Starting Nginx..."
exec nginx -c /app/nginx.conf -g 'daemon off;'