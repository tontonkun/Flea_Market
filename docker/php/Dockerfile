FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www

# Laravel の依存関係インストールとキャッシュ
COPY . /var/www
WORKDIR /var/www

RUN composer install --no-dev --optimize-autoloader

# Laravel の key 生成とキャッシュ処理（ビルド時 or 起動時に）
RUN php artisan config:cache

RUN php artisan storage:link
