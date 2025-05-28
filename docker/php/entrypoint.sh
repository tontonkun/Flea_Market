#!/bin/sh

cd /var/www

# Render環境変数から .env を生成（初回のみ）
if [ ! -f .env ]; then
  cat <<EOF > .env
APP_NAME=Laravel
APP_ENV=${APP_ENV}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG}
APP_URL=${APP_URL}
LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
EOF
fi

# パーミッション調整
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Laravelキャッシュクリア & 再生成
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# マイグレーション実行（失敗してもクラッシュしない）
php artisan migrate --force || true

# Laravelのビルトインサーバ起動（Renderはこの形式でOK）
php artisan serve --host=0.0.0.0 --port=${PORT}
