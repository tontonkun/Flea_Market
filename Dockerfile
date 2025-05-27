FROM php:8.2

# PHP拡張と依存インストール
RUN apt update && apt install -y \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    curl \
    git \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# Composer インストール
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

# 作業ディレクトリ
WORKDIR /var/www

# Laravel アプリと .env をコピー
COPY ../../src /var/www
COPY ../../src/.env /var/www/.env

# パーミッション
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Composer install
RUN composer install --no-dev --optimize-autoloader

# Laravel コマンド実行
RUN test -f artisan && php artisan config:cache && php artisan storage:link

EXPOSE 8080

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
