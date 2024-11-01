ARG PHP_VERSION=8.2
ARG OS_VERSION=3.16
FROM php:${PHP_VERSION}-fpm-alpine${OS_VERSION}

# UPDATE
RUN apk update && apk upgrade

# EXTRA
RUN apk add vim git curl make mc zip unzip

# REQ 4 PECL EXTENSIONS (TODO: how to clean .build-dependencies)
RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS

# BASE REQ
RUN docker-php-ext-install -j "$(nproc)" \
    opcache \
    bcmath \
    exif

## ZIP
RUN apk add libzip-dev \
    && docker-php-ext-install zip

# DB
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli

RUN apk add curl-dev openssl openssl-dev \
    && pecl channel-update pecl.php.net \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# MEMCACHE
RUN apk add libmemcached libmemcached-dev zlib zlib-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && apk del libmemcached-dev zlib-dev

# APCU
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# GMP & INTL
RUN apk add gmp-dev icu-dev \
    && docker-php-ext-install gmp intl 

# GD
RUN apk add freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j "$(nproc)" gd


# IMAGEMAGICK
RUN apk add php82-pecl-imagick --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    && apk add --update imagemagick imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# XDEBUG
RUN apk add --no-cache --update linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del linux-headers

# CLEAN (not sure it works because is executed on a different layer)
RUN pecl clear-cache && apk del --purge --no-cache .build-dependencies
# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/app-php.ini

WORKDIR /var/www
#USER www-data

HEALTHCHECK CMD curl --fail http://localhost:8080/ping || exit 1

CMD ["php-fpm"]
