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

# テストコード

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

## テストコード一覧

| 機能            | ファイル名                | 内容                                                                 |
|-----------------|---------------------------|----------------------------------------------------------------------|
| ログイン機能     | TestForUserLogin.php       | メールアドレスが入力されていない場合、バリデーションメッセージが表示される |
|                 |                           | パスワードが入力されていない場合、バリデーションメッセージが表示される |
|                 |                           | 入力情報が間違っている場合、バリデーションメッセージが表示される     |
|                 |                           | 正しい情報が入力された場合、ログイン処理が実行される                 |
| ログアウト機能   | TestForUserLogout.php      | ログアウトができる                                                  |
| 会員登録機能     | TestForUserRegistration.php | 名前が入力されていない場合、バリデーションメッセージが表示される   |
|                 |                           | メールアドレスが入力されていない場合、バリデーションメッセージが表示される |
|                 |                           | パスワードが入力されていない場合、バリデーションメッセージが表示される |
|                 |                           | パスワードが7文字以下の場合、バリデーションメッセージが表示される    |
|                 |                           | パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される |
|                 |                           | 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される |
| 商品一覧取得     | TestForGettingItemList.php | 全商品を取得できる                                                  |
|                 |                           | 購入済み商品は「Sold」と表示される                                   |
|                 |                           | 自分が出品した商品は表示されない                                     |
| マイリスト一覧取得 | TestForGettingFavoriteList.php | いいねした商品だけが表示される                                       |
|                 |                           | 購入済み商品は「Sold」と表示される                                   |
|                 |                           | 自分が出品した商品は表示されない                                     |
|                 |                           | 未認証の場合は何も表示されない                                       |
| 商品検索機能     | TestForSearchFunction.php  | 「商品名」で部分一致検索ができる                                    |
|                 |                           | 検索状態がマイリストでも保持されている                              |
| 商品詳細情報取得 | TestForItemDetail.php      | 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容） |
|                 |                           | 複数選択されたカテゴリが表示されているか                           |
| いいね機能 | TestForFavoriteFunction.php | いいねアイコンを押下することによって、いいねした商品として登録され、いいね合計値が増加表示される。 |
|                 |                           | いいねアイコンが押下された状態では色が変化する |
|                 |                           | いいねが解除され、いいね合計値が減少表示される |
| コメント送信機能 | TestForCommentFunction.php | ログイン済みのユーザーはコメントを送信できる                        |
|                 |                           | ログイン前のユーザーはコメントを送信できない                        |
|                 |                           | コメントが入力されていない場合、バリデーションメッセージが表示される |
|                 |                           | コメントが255字以上の場合、バリデーションメッセージが表示される   |
| 商品購入機能     | TestForPurchaseFunction.php | 「購入する」ボタンを押下すると購入が完了する                        |
|                 |                           | 購入した商品は商品一覧画面にて「sold」と表示される                  |
|                 |                           | 「プロフィール/購入した商品一覧」に追加されている                   |
| 支払い方法選択機能 | TestForPaymentFunction.php | 小計画面で変更が即時反映される                                      |
| 配送先変更機能   | TestForShippingFunction.php | 送付先住所変更画面にて登録した住所が商品購入画面に反映されている   |
|                 |                           | 購入した商品に送付先住所が紐づいて登録される                        |
| ユーザー情報取得 | TestForMyPage.php          | 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧） |
| ユーザー情報変更 | TestForProfile.php         | 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所） |
| 出品商品情報登録 | TestForPosting.php         | 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格） |




# 使用技術

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


# MailHogに関して

メール認証実装のために使用しています。メール認証を行う際は、

`http://localhost:8025/#`

にアクセスしてください

# ER図

※5/5での実装における追加内容を赤にしています

![image](https://github.com/user-attachments/assets/69311072-ed1a-4cc6-a54a-6d4fae23349b)







