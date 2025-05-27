FROM php:8.2

# システム依存パッケージと PHP 拡張のインストール
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

# 作業ディレクトリを Laravel プロジェクトルートに設定
WORKDIR /var/www

# Laravel アプリのコードと .env をコピー
COPY ./src /var/www
COPY ./src/.env /var/www/.env

# ストレージとキャッシュディレクトリのパーミッションを設定
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Composer インストール（--no-dev 本番用）
RUN composer install --no-dev --optimize-autoloader

# Laravel のキャッシュ・シンボリックリンク設定
RUN test -f artisan && php artisan config:cache && php artisan storage:link

# ポート開放（Render で必要）
EXPOSE 8080

# アプリケーション起動コマンド
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
