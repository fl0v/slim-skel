ARG PHP_VERSION=8.2
ARG OS_VERSION=3.16
FROM php:${PHP_VERSION}-fpm-alpine${OS_VERSION}

# UPDATE
RUN apk update && apk upgrade

# EXTRA
RUN apk add vim git curl make mc zip unzip

# REQ 4 PECL EXTENSIONS
RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS

# MEMCACHE
RUN apk add libmemcached libmemcached-dev zlib zlib-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# APCU
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# GMP & INTL
RUN apk add gmp-dev icu-dev \
    && docker-php-ext-install gmp intl

RUN docker-php-ext-install opcache bcmath

# GD
RUN apk add \
    freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j$(nproc) gd \
    && apk del freetype-dev libpng-dev libjpeg-turbo-dev 

# TODO: add imagemagick


# CLEAN
RUN pecl clear-cache && apk del --purge --no-cache ${PHPIZE_DEPS}

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www

HEALTHCHECK CMD curl --fail http://localhost:8080/ping || exit 1

CMD ["php-fpm"]
