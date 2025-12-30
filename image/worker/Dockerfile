FROM php:8.4-fpm-bookworm

WORKDIR /var/www/html

# Install necessary libraries and PHP extensions
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        supervisor \
        libfreetype6-dev \
        libfontconfig1-dev \
        libpng-dev \
        libjpeg-dev \
        libc-dev \
        unzip \
        curl \
        libzip-dev \
        libpq-dev \
        pkg-config \
        libssl-dev \
        zlib1g zlib1g-dev \
        libxml2-dev \
        libonig-dev \
        libmemcached-dev \
        libgmp-dev \
        default-mysql-client \
        libsqlite3-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install \
        bcmath \
        gd \
        pcntl \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        mysqli \
        exif \
        zip \
        intl \
        soap \
        gmp \
        opcache && \
    pecl install redis && docker-php-ext-enable redis && \
    apt-get remove --purge -y $PHPIZE_DEPS \
    && rm -rf /tmp/* \
    && apt -y autoremove \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/*

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
