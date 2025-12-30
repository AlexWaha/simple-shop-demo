#!/bin/sh

addgroup -g 1000 laravel 2>/dev/null || true
adduser laravel -G laravel -s /bin/sh --disabled-password 2>/dev/null || true

mkdir -p /var/www/html
chown -R laravel:laravel /var/www/html

# Wait for PHP-FPM to be ready
echo "Waiting for PHP-FPM..."
while ! nc -z php 9000 2>/dev/null; do
    sleep 1
done
echo "PHP-FPM is ready"

# Start Nginx
exec nginx -g 'daemon off;'