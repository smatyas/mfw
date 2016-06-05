FROM php:5.6-fpm

ENV TERM xterm
RUN apt-get update \
    && apt-get install -y wget nano less git zip unzip mysql-client libicu-dev zlib1g-dev libmemcached-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && pecl install xdebug memcached \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "extension=$(find /usr/local/lib/php/extensions/ -name memcached.so)" > /usr/local/etc/php/conf.d/memcached.ini

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install pdo_mysql opcache gd
ADD custom.ini /usr/local/etc/php/conf.d/

RUN wget https://getcomposer.org/composer.phar -O /usr/local/bin/composer \
    && chmod a+x /usr/local/bin/composer

RUN usermod -u 1000 www-data
RUN usermod -G staff www-data

# Creating 'var' inside the container, so php can write it. (It won't be accessible from the host.)
RUN mkdir -p /usr/share/nginx/html/var
RUN chown www-data:www-data /usr/share/nginx/html/var

# Make www-data's home writeable
RUN chown www-data:www-data /var/www

RUN touch /var/log/php-fpm.log
RUN chown www-data:www-data /var/log/php-fpm.log

USER www-data
WORKDIR /usr/share/nginx/html
VOLUME ["/usr/share/nginx/html/var"]
