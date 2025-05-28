#!/usr/bin/env bash
# exit on error
set -o errexit

# Composerのインストールと依存関係のインストール
composer install --no-dev --prefer-dist

# キャッシュのクリアと再生成
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

# マイグレーションの実行
php artisan migrate --force

# シーディングの実行
php artisan db:seed --force
