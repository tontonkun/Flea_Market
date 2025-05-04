# アプリケーション名
Flea_Market

# 環境構築

## 以下のツールをインストールしてください：

・Docker: Docker公式サイトからインストール

・Docker Compose: Dockerに含まれているはずですが、必要に応じてインストール方法を確認

・Git: Git公式サイトからインストール

## インストール手順

１，リポジトリのクローン（Github）

```
git clone https://github.com/tontonkun/Flea_Market.git

cd your-repository
```

２，コンテナ立ち上げ（Docker Compose）

`docker-compose up -d`

３，環境設定ファイルのコピー

`docker-compose exec php bash`

実行後、

`cp .env.example .env`
※環境変数は適宜変更

４，アプリケーションキー作成

`docker-compose exec php bash`

実行後、

`php artisan key:generate`

５，データベースのマイグレーションと初期データのシーディング

`docker-compose exec php bash`

実行後、

`php artisan migrate`

`php artisan db:seed`

※MySQLの接続設定

```
DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```

## テストコード実行方法

１，テスト用データベース作成

`docker ps`

でMySQLコンテナのIDを確認し、

`docker exec -it {{ MySQL コンテナID}} mysql -u root -p`

を実行してrootユーザ（管理者)でMySQLコンテナに入り（パスワードは'root'）、

`> CREATE DATABASE demo_test;`

で専用データベース作成（'> SHOW DATABASES;'で確認）

２，テストコード実行

`docker-compose exec php bash`

実行後、

`vendor/bin/phpunit tests/Feature/{{任意のテストファイル名}}`


## 使用技術

Laravel 8.x

Github

Docker

MySQL

nginx

HTML,CSS

PHP

javascript

MailHog

Stripe決済


## MailHogに関して

メール認証実装のために使用しています。メール認証を行う際は、

`http://localhost:8025/#`

にアクセスしてください

## ER図

※5/5での実装における追加内容を赤にしています

![image](https://github.com/user-attachments/assets/69311072-ed1a-4cc6-a54a-6d4fae23349b)







