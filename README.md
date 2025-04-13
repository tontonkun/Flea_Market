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
`php artisan db: seed`

※MySQLの接続設定

```
DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```


## 使用技術

Laravel 8.x

Github

Docker

MySQL

nginx

HTML,CSS

PHP

javascript

## ER図

![image](https://github.com/user-attachments/assets/4a5c9dcc-633a-4e7c-b414-3d3a1c8906c2)






