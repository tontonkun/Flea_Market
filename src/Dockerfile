FROM php:8.2-fpm

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

# Laravel アプリの全コードをコピー（Docker ビルドコンテキストが Laravel ルートになっている必要あり）
COPY . .

# Composer インストール
RUN composer install --no-dev --optimize-autoloader

# Laravel 必須コマンド（artisanが見えてる前提）
RUN test -f artisan && php artisan config:cache && php artisan storage:link

# ポート開放（Renderでは PORT 環境変数に従って CMD でバインド）
EXPOSE 8080

# アプリ起動
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
