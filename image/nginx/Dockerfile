FROM nginx:stable-alpine

ENV NGINX_UPSTREAM=php:9000
ENV NGINX_ALLOWS_CORS=true

RUN apk add --no-cache netcat-openbsd

WORKDIR /var/www/html

# Copy the entrypoint script
COPY nginx-entrypoint.sh /usr/local/bin/nginx-entrypoint.sh
RUN chmod +x /usr/local/bin/nginx-entrypoint.sh

EXPOSE 80 443