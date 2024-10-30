FROM php:8.3-apache

RUN mkdir -p /app/var
RUN chmod 777 /app/var

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y apt-utils wget unzip autoconf automake make gcc g++ bash libicu-dev libzip-dev git
RUN docker-php-ext-install -j$(nproc) bcmath intl zip pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN a2enmod rewrite
COPY /infrastructure/app/base/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY /infrastructure/app/base/apache/app_directory.conf /tmp/app_directory.conf
RUN cat /tmp/app_directory.conf >> /etc/apache2/apache2.conf
RUN mkdir -p /var/lock/apache2 /var/run/apache2

COPY /infrastructure/app/base/php/php.ini /usr/local/etc/php/php.ini
