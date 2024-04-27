FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libssl-dev \
    libpq-dev \
    git \
    autoconf \
    g++ \
    make \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

RUN git clone https://github.com/swoole/swoole-src.git /usr/src/swoole-src \
    && cd /usr/src/swoole-src \
    && phpize \
    && ./configure --enable-openssl --enable-http2 --enable-swoole --enable-swoole-pgsql \
    && make && make install \
    && docker-php-ext-enable swoole

COPY . /var/www/html/

EXPOSE 80
