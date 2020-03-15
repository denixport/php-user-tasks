FROM php:7.4-fpm

RUN apt-get -y update
RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer
RUN docker-php-ext-install sqlite3
RUN pecl install xdebug-2.7.2 && docker-php-ext-enable xdebug
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/timezone.ini
