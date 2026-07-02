# Rese
飲食店予約サービスアプリです。
<img width="1295" height="665" alt="スクリーンショット (5583)" src="https://github.com/user-attachments/assets/a2461dcb-6f13-4aac-8b8c-e5af9a578352" />

# アプリ概要
Reseは、ユーザーが飲食店を検索・予約できる予約管理サービスです。

管理者・店舗代表者・一般ユーザーの3つの権限を持ち、それぞれ異なる機能を利用できます。
<img width="1295" height="662" alt="スクリーンショット (5582)" src="https://github.com/user-attachments/assets/7e9ab6d8-3972-4b7c-8632-bc5340e06e37" />

# 作成した目的

Laravelを用いた実践的なWebアプリケーション開発として、下記を組み合わせた総合的な予約管理システムを構築するために作成しました。
- 認証機能
- 権限管理機能
- 店舗予約機能
- 口コミ機能
- 決済機能
- メール通知機能

Laravel（Blade）での従来のCRUDから、 徐々にNext.jsを用いたSPA構成へ移行し、 API設計・認証・フロント連携の理解を深めることも進めてます。

# 使用技術

## バックエンド

- PHP 8.0
- Laravel 8.4
- MySQL 8.0.26
- PHPUnit
- MailHog
- html(blade)
- CSS
- JavaScript
- Inertia-Laravel

## フロントエンド(開発中)
本アプリはLaravel Bladeを中心に構築して、Monorepo（モノレポ）タイプで、バックエンドとの統合された形です。

- Next.js
- React
- Node.js

## 開発環境

- Docker
- Docker Compose
- phpMyAdmin

# 環境構築

## 1 Gitリポジトリをクローン

```bash
git clone https://github.com/shoyama1010/rese.git
```

## 2 Dockerコンテナを起動

```bash
docker-compose up -d --build
```

## 3 PHPコンテナへログイン

```bash
docker-compose exec php bash
```

## 4 Composerパッケージをインストール

```bash
composer install
```

## 5 .envファイルを作成

```bash
cp .env.example .env
```

## 6 .env設定

```env
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="ReseManagement"

STRIPE_KEY=取得した公開キー
STRIPE_SECRET=取得したシークレットキー
```

## 7 アプリケーションキー生成

```bash
php artisan key:generate
```

## 8 マイグレーション実行

```bash
php artisan migrate
```

## 9 ダミーデータ投入

```bash
php artisan db:seed
```

# URL

## ローカル環境（アプリケーション）

http://localhost

## phpMyAdmin

http://localhost:8081

## MailHog

http://localhost:8025

## 本番環境（AWS：EC2）

http://（IPv4 アドレス）

# テーブル設計

<img width="923" height="482" alt="スクリーンショット (5575)" src="https://github.com/user-attachments/assets/f17cda8b-681f-4424-9c9b-10b3113977ef" />

# ER図

<img width="3149" height="2393" alt="mermaid-diagram (3)" src="https://github.com/user-attachments/assets/90ec6617-b2a7-4131-b362-d99bf5bb4e96" />

# １．実装機能（バックエンドでの表示）

## 一般ユーザー機能

- 会員登録
- ログイン
- ログアウト
- 店舗一覧表示
- 店舗検索
- エリア検索
- ジャンル検索
- お気に入り登録
- お気に入り解除
- 店舗予約
- 予約確認
- 口コミ投稿
- 口コミ編集
- 口コミ削除
- 口コミ一覧表示

## 店舗代表者機能

- ログイン
- 自店舗予約一覧表示
- 予約者へのメール通知
- 店舗情報管理

## 管理者機能

- ログイン
- 店舗代表者登録
- 店舗代表者一覧表示
- 店舗口コミ管理
- CSVインポート（開発中）

# 認証・権限管理(管理者及び店舗代表者用管理画面ログイン機能)
<img width="1196" height="615" alt="スクリーンショット (5761)" src="https://github.com/user-attachments/assets/b4f7bfff-8c48-4d31-9fca-437160711c98" />

以下3種類の認証を実装しています。

- User
- Owner
- Admin

Guardを利用したマルチログイン認証を採用しています。

管理者アカウント

email -> admin@example.com

password -> admin1234

<img width="1202" height="659" alt="スクリーンショット (5760)" src="https://github.com/user-attachments/assets/4dfe1eac-e083-4d73-8331-b963e34fa1c2" />

# メール機能

MailHogを利用したメール送信機能を実装しています。

- 店舗代表者から予約者への通知メール送信
- メール内容確認機能

# CSVインポート機能

管理者画面より店舗情報CSVをインポートできますが、開発調整中です。

# バリデーション

FormRequestを採用しています。

実装箇所

- 会員登録
- ログイン
- 店舗代表者登録
- 店舗予約
- 口コミ投稿
- 口コミ編集
- CSVアップロード

# ２．実装機能（フロントエンド表示：開発中）

## 一般ユーザー機能
- ログイン（開発中）
- 店舗一覧表示
- 店舗検索
- エリア検索
- ジャンル検索

### アプリケーション 

http://localhost/3000

<img width="1239" height="675" alt="スクリーンショット (5658)" src="https://github.com/user-attachments/assets/79cd178b-0b4e-4ee6-9a8a-df2b723c988e" />

# テスト

## Featureテスト

### OwnerReservationMailTest

- 店舗代表者は予約一覧を閲覧できる
- 店舗代表者は予約者へメール送信できる
- 他店舗の予約にはメール送信できない

### ReviewFeatureTest

- ログインユーザーは口コミを投稿できる
- ログインユーザーは自分の口コミを更新できる
- ログインユーザーは自分の口コミを削除できる

# テスト実行方法

## PHPコンテナへログイン

docker-compose exec php bash

## テストDB作成

```sql
CREATE DATABASE rese_test;
```

## テスト用マイグレーション

php artisan migrate --env=testing

## 全テスト実行

php artisan test --env=testing

## OwnerReservationMailTest実行

php artisan test --env=testing --filter=OwnerReservationMailTest

## ReviewFeatureTest実行

php artisan test --env=testing --filter=ReviewFeatureTest

# 工夫した点

## バックエンド

- Guardを利用したマルチログイン認証
- Policyによる口コミ編集権限制御
<img width="1293" height="658" alt="スクリーンショット (5641)" src="https://github.com/user-attachments/assets/14117a23-1f55-4875-8e5f-d5b3a223d503" />
<img width="1269" height="662" alt="スクリーンショット (5640)" src="https://github.com/user-attachments/assets/a2cef40a-b644-46a9-baf9-71af12b5cf86" />

- MailHogを利用したメール通知機能
- PHPUnitによるFeatureテスト実装

# 苦労した点
口コミ編集機能において、最初「UI崩れ」と店舗消えが起こりましたが、正しい画面構成（店舗情報、予約フォーム、全ての口コミ情報ボタン、javascriptで口コミ表示）のために、コントローラーを見直すのが以外と難しかったです。

# 将来への改善点
- 現時点ではアプリ全体をSPA化しているわけではなく、一般ユーザー画面や主要機能はLaravel Bladeで動作しています。
- 今後、管理画面を中心にNext.js + Laravel API構成へ拡張予定です。
- 認証をfortifyでは設定していなかった（与えられた案件の要件には、当時それは入ってなかった）ので、次回作り直すか同じようものを作る時は、fortifyを入れるようにしたいです。
- 上記fortifyを入れた後、「メール認証機能」も整備したいです。
- SPA開発が未達なので、今後Inertia.jsを使用し、モノリシックなLaravelアプリケーションとして、下記の理由で構築していきます。
- プロジェクト構造がシンプル（1つのリポジトリ）
- CORSやAPI認証の設定が不要
- フロントエンドとバックエンドの依存関係が明確
