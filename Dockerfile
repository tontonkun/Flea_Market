FROM php:8.2

# 必要パッケージとPHP拡張
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
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer self-update

# 作業ディレクトリを Laravel プロジェクトルートに設定
WORKDIR /var/www

# Laravel アプリのコードをコピー
COPY ./src /var/www

# .env が無ければ仮コピー（必要に応じて）
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# 権限設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Composer インストール
RUN composer install --no-dev --optimize-autoloader

# Laravel 初期コマンド
RUN test -f artisan && php artisan config:cache && php artisan storage:link

# ポート開放
EXPOSE 8080

# アプリ起動
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
