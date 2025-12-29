#!/bin/sh

addgroup -g 1000 laravel
adduser laravel -G laravel -s /bin/sh --disabled-password

mkdir -p /var/www/html
chown -R laravel:laravel /var/www/html

# Start Nginx
exec nginx -g 'daemon off;'