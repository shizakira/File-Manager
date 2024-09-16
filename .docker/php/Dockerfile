FROM php:8.2-fpm

COPY .docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY .docker/php/php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

COPY . .

RUN chmod -R 0777 /var/www/vendor

RUN if [ ! -d "vendor" ]; then composer install --no-interaction --optimize-autoloader; fi

RUN mkdir -p /var/run/php

RUN chmod -R 0777 /var/run/php

RUN chmod -R 0777 /var/www/src/uploads 

CMD ["php-fpm"]
