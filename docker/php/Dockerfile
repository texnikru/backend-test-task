FROM php:8.4.12-fpm

RUN pecl install xdebug-3.4.5 && docker-php-ext-enable xdebug \
    && docker-php-source delete

COPY ./conf.d /usr/local/etc/php/conf.d

WORKDIR /app
