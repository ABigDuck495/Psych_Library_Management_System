#!/bin/sh
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Running database seeds..."
php artisan db:seed --force

echo "Starting application..."
exec /usr/bin/supervisord -c /etc/supervisord.conf