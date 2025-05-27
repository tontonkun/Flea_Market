FROM php:8.2

# 必要なパッケージをインストール
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

# PHP 設定追加（必要に応じて）
COPY php.ini /usr/local/etc/php/

# 作業ディレクトリ
WORKDIR /var/www

# Laravel アプリのコードをコピー
COPY ./src /var/www

# 書き込み権限
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Composer install
RUN composer install --no-dev --optimize-autoloader

# Laravel の設定キャッシュ＆ストレージリンク
RUN test -f artisan && php artisan config:cache && php artisan storage:link

# ポート開放（Render 対応）
EXPOSE 8080

# アプリ起動
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
