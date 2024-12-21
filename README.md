# coachtech 勤怠管理アプリ

## 概要

このプロジェクトは、従業員の出退勤を管理するためのシンプルで使いやすいアプリケーションです。従業員の打刻、休憩時間の管理、そして出勤データのフィルタリングを容易に行うことができます。

## 作成した目的
coachtech 勤怠管理システムを作成した

## アプリケーションURL
- coachtech 勤怠管理アプリのURL : http://localhost/attendance/
- 管理者用ログイン画面 : http://localhost/admin/login/
- MailCatcherのURL : http://localhost:1080

## GitHubのリポジトリ
- https://github.com/sakanadaketabetetai/create_attendance.git

## 機能一覧
### 一般ユーザー
- 会員登録機能 ( メール認証付 )
- ログイン及びログアウト機能
- 勤務及び休憩打刻機能
- 月別勤怠情報取得
- 勤怠詳細情報取得
- 申請一覧情報取得表示及び勤怠情報変更申請機能

### 管理者ユーザー
- ログイン及びログアウト機能
- ユーザ一覧情報取得
- ユーザー日付別勤怠情報取得
- ユーザー勤怠情報詳細情報取得
- ユーザー勤怠情報変更
- ユーザー別勤怠情報取得
- ユーザー勤怠情報修正
- ユーザー勤怠情報変更申請承認機能
- ユーザー勤怠情報CSV出力
- ユーザー修正申請一覧（フィルタリング機能）

## 使用技術 ( 実行環境 )
- Docker 26.1.4
- Laravel 8.x
- php 7.4.9-fpm
- mysql 8.0.26
- mailcatcher ( メール認証機能確認用 )

## 特徴
- リアルタイムでの打刻記録
- 休憩時間の追跡
- 日別の出勤データのフィルタリング
- ユーザーフレンドリーなインターフェース

## ER図
![attendance_er](https://github.com/user-attachments/assets/e8a754d6-70f2-411e-b2b3-5f70486233f3)

## 環境構築

### Dockerビルド

1. ```bash 
   git clone git@github.com:sakanadaketabetetai/create_attendance.git
   ```
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build


### Laravel環境構築

1. PHPコンテナにアクセス:
    ```bash
    docker-compose exec php bash
    ```
2. 依存関係をインストールします:
    ```bash
    composer install
    ```
3. 環境変数ファイルをコピーします:
    ```bash
    cp .env.example .env
    ```
4. .envに以下の環境変数を追加
    Mysqlに関する設定
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel_db
    DB_USERNAME=laravel_user
    DB_PASSWORD=laravel_pass
    ```
    メールに関する設定
    ※下の変数設定はMailCatcherを使用する場合の設定であり、自身のメールアドレスを使用する場合は、必要に応じて設定値を変更する
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=mailcatcher  //自身のメールサーバーを入力 
    MAIL_PORT=1025 //使用するポートを入力
    MAIL_USERNAME=null //自身のメールアドレス
    MAIL_PASSWORD=null //自身のメールサーバーにアクセスするパスワード
    MAIL_ENCRYPTION=null //ssl
    MAIL_FROM_ADDRESS=no-reply@example.com //自身のメールアドレス
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    APP環境
    ```bash
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=　     //php artisan key:generate実行時に自動で生成される
    APP_DEBUG=true
    APP_URL=http://localhost //AWS ec2インスタンスにデプロイする場合、AWSパブリックIPv4アドレスを入力
    ```
    
5. アプリケーションキーを生成します:
    ```bash
    php artisan key:generate
    ```
6. マイグレーションを実行します:
    ```bash
    php artisan migrate
    ```
7. シーディングを実行します:
    ```bash
    php artisan db:seed
    ```


## 基本的な使い方

### 一般ユーザー
1. http//:localhost/attendance/login/にアクセスして、ログインします。

2. 「勤務開始」をクリックして、勤務を開始します。

3. 「勤務終了」をクリックして、勤務を終了します。

4. 「休憩開始」をクリックして、休憩を開始します。

5. 「休憩終了」をクリックして、休憩を終了します。

6. 「日付一覧」をクリックすると、日付別の勤怠情報を閲覧できます。

7. 「勤怠一覧」をクリックすると、自身の勤怠一覧ページに移動し、月ごと
　　 の勤怠情報を閲覧できます。

8. 自身の勤怠一覧ページの中にある「詳細」ボタンをクリックすると、勤怠詳細画面が
　 表示され、各項目の修正申請が可能。

8.  会員登録する場合は、「氏名」、「メールアドレス」、「パスワード」を入力し
　  入力したメールアドレスに確認メールが送信されます。

### 管理者ユーザー
1. http//:localhost/admin/login/にアクセスして、ログインします。

2. 管理者ユーザーは日次勤怠一覧を確認することができる。

3. 各勤怠の詳細を確認・修正をすることができる。

4. スタッフ一覧を確認することができる。

5. スタッフ毎の月次勤怠一覧を確認することができる。

6. 修正申請一覧を確認することができる。

7. 修正申請の詳細を確認し、承認することができる。
