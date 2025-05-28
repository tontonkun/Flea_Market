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

# Laravel アプリをコピー
COPY ../../src /var/www

# entrypoint.sh をコピー
COPY ../../docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Composer install（.env なしでOKな範囲で）
RUN composer install --no-dev --optimize-autoloader

# Laravel の public/storage シンボリックリンク作成用（実行は entrypoint.sh に移動しても可）
RUN test -f artisan && php artisan storage:link || true

# ポート開放（Renderが動的に割り当てる）
EXPOSE 8080

# 起動処理を entrypoint.sh に任せる
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
