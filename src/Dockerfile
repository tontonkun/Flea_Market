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

# 作業ディレクトリを Laravel プロジェクトの src に設定
WORKDIR /var/www/src

# Laravel アプリのソースコードを src 配下にコピー
COPY ./src ./ 

# ディレクトリ書き込み権限を src 内の storage と bootstrap/cache に付与
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Composer インストールを src 配下で実行
RUN composer install --no-dev --optimize-autoloader

# Laravel 必須コマンド（artisanが見えてる前提）
RUN test -f artisan && php artisan config:cache && php artisan storage:link

# ポート開放（Renderでは PORT 環境変数に従って CMD でバインド）
EXPOSE 8080

# アプリ起動
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
