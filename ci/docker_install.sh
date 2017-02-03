#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -y
apt-get install -y git wget zip unzip mysql-client libicu-dev zlib1g-dev libmemcached-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev
pecl install xdebug
docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
docker-php-ext-install pdo_mysql opcache gd
