# Laravel

## 開発環境について

### 前提

- OS
  - Windows
-PHP
  - php-8.2.10-Win32-vs16-x64
    - x64 Thread Safe版をダウンロード
      - https://windows.php.net/download#php-8.2
  - PHP Xdegug (PHPでdebugが使用できるようになる)
    - https://xdebug.org/download
      - Thread Safe版をダウンロード
- Composer (Laravel初回セットアップに使用)
  - https://getcomposer.org/download/
  - Windows InstallerのComposer-Setup.exeをインストール
- Laravel
  - https://readouble.com/laravel/10.x/ja/installation.html
- DB
  - mariadb
    - https://mariadb.org/download/
- Node.js
  - https://nodejs.org/ja
    - Laravelが要求するversionをインストール
- VS Codeで使える拡張機能
  - PHP Extension Pack
    - PHP Debug
    - PHP IntelliSense
  - Laravel Extention Pack
    - Laravel Blade Snippets
    - Laravel Snippets
    - Laravel Artisan
    - Laravel goto view
    - laravel-jump-controller
    - Laravel Extra intellisense
    - DotENV
    - EditorConfig for VS Code
    - laravel-goto-components
    - Laravel Blade formatter (これイイ)
    - Laravel Create View
    - Laravel Blade Wrapper
  - Project Manager
    - プロジェクト登録したものを呼び出せるようになる
  - ESLint
    - JavaScript の自動コードチェック
  - HTMLHint
    - HTML の自動コードチェック

### ローカルDBの構築

インストーラを用いてインストールを行った後の設定  
`root` のパスワードは `root` にしている前提とする

#### Path の設定

システム環境変数のPathにダウンロードした `MariaDB` フォルダ配下の `bin` フォルダを指定

#### my.ini の設定

インストーラを使用して通常のインストールをしていると、`MariaDB` フォルダ配下の `data` フォルダに `my.ini` がある（はず）  

`my.ini` をテキストエディタで開き、編集する

```ini
[mysqld]

# 追記する
character_set_server = utf8mb4
collation_server = utf8mb4_bin
```

編集したら、Windowsのサービスの画面でMariaDBのサービスを再起動する

#### ユーザー追加

コマンドプロンプトで作業を行う

```bash
mysql -uroot -proot
```

MariaDBに接続後

```sql
grant all privileges on *.* to keijiban@localhost identified by 'password' with grant option;
grant all privileges on *.* to keijiban@'%' identified by 'password' with grant option;
```

#### DB作成

コマンドプロンプトでDBを構築する

```sql
create database keijiban
```

DBを構築したら掲示板テーブルを作成する

```sql
CREATE TABLE `keijiban` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `user_name` varchar(50) NOT NULL COMMENT 'ユーザ名',
  `contents` varchar(1024) NOT NULL COMMENT '内容',
  `created_at` datetime NOT NULL COMMENT '登録日付',
  `updated_at` datetime DEFAULT NULL COMMENT '更新日付',
  PRIMARY KEY (`id`)
) COMMENT='掲示板'
```

### PHPのインストール

PHPをダウンロードしたら任意のディレクトリに配置する  
今回は `C:\php` に配置

システム環境変数のPathに以下を追加する

```
C:\php\php-8.2.10-Win32-vs16-x64
```

追加したら `C:\php\php-8.2.10-Win32-vs16-x64` 配下にある `php.ini-development` をコピペし `php.ini` に名前を変える  
`php.ini `を開き、以下の部分を先頭の;を外し変更する(後で行うnginxにも関係ある(らしい、、))

```ini
extension_dir="ext"
extension=curl
extension=mbstring
extension=openssl
extension=pdo_mysql
date.timezone=Asia/Tokyo
```

コマンドプロンプトで下記のコマンドを実行し、phpが正しくインストールされていればOK

```bash
php -v
```

### Xdebugのインストール

ダウンロードしたファイルを `php_xdebug.dll` とリネームし、 `C:\php\php-8.2.10-Win32-vs16-x64\ext` 配下へ移動する  
そうしたら `php.ini` へ以下の設定を追加する

```ini
zend_extension=xdebug
xdebug.mode=debug
xdebug.start_with_request=yes
```

### Laravelのインストール

PHPとComposerをインストールしたら、Laravelの新規プロジェクトを作成する

```bash
composer create-project laravel/laravel example-app
```

プロジェクト作成後、Laravelローカル開発サーバを起動

```bash
cd example-app

php artisan serve
```

開発サーバを起動後、Webブラウザで `http://localhost:8000` にアクセスできるようになる

#### コントローラの作成

ありがたい事にLaravelにはコントローラなどを一瞬で作れるコマンドが存在する（といってもがわだけだが、、、）  
そのコマンドがこちら

```bash
php artisan make:controller KeijibanController
```

#### モデルの作成

```bash
php artisan make:model Keijiban
```

#### ビューの作成

```bash
php artisan make:view keijiban
```

#### Laravel Breezeのインストール

またまたありがたい事にLaravelにはログイン、ユーザー登録、パスワードリセット、メール確認、パスワード確認など  
すべての認証機能を最小かつシンプルに実装したものがある  
それこそこのLaravel Breezeだ

Composerを使用してLaravel Breezeをインストールする

```bash
composer require laravel/breeze --dev
```

Laravel BreezeをインストールしたらArtisanコマンドを実行

```bash
php artisan breeze:install

php artisan migrate
npm install
npm run dev
```

これで今日から君もLaravelマスターだ

### Webサーバ構築

なんて、これくらいでマスターになれると思ったら大間違い、甘えるな！  
ここからが本番だ

今まではローカル環境で表示していたがここからはWebサーバに切り替える

### 必要な物

- nginx
  - http://nginx.org/en/download.html
    - Stable version(zip版)をインストール
- php-fpm

#### nginxのインストール

まず必要なのがこのnginx  
正直これがなんなのかはあまり分からない、2年後くらいなら分かってるかも

nginxをインストールしたら任意のディレクトリに設置する  
今回は `C:\` に配置

次は設定ファイルを編集  
設定ファイル `C:\nginx-1.24.0\conf\nginx.conf` をテキストエディタで開き、以下のように変更する

```conf
    server {
         listen       8000;
         listen       [::]:8000;
         server_name  localhost;
         root   C:\Git\PHP\laravel\example-app\public;
         
         add_header X-Frame-Options "SAMEORIGIN";
         add_header X-Content-Type-Options "nosniff";
         
         index index.php;
         
         charset utf-8;

        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
        
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
        
        error_page 404 /index.php;

        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:8000;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        location ~ /\.ht {
            deny  all;
        }
    }
```

変更したら動くかどうかテストする

1. 設定したパスに適当な `phpinfo.php` を設置
2. `C:\php\php-8.2.10-Win32-vs16-x64` 配下にある `php-cgi.exe` をコマンドプロンプトで実行
    ```bash
    php-cgi -b 127.0.0.1:8000
    ```
3. `C:\nginx-1.24.0` 配下にある `nginx.exe` をコマンドプロンプトで実行
    ```bash
    start nginx
    ```
4. `http://localhost:8000` に接続し、無事 `index.php` が表示されればOK
5. nginxを終了する
    ```bash
    nginx -s stop
    ```
6. phpを終了する(Ctrl + C)

これで君もハイパーウルトラスーパーLaravelマスターだ
