FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/src

RUN docker-php-ext-install pdo_mysql

COPY .docker/php/php.ini /usr/local/etc/php/php.ini

CMD ["php-fpm"]
