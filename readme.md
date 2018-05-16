# AndyPHP - PHP 运行环境一键安装包
能够在线开设虚拟主机、FTP、MySQL。适用于 x64 位系统，不能运行在 Windows XP 和 2003。 支持: Windows 7 SP1, Vista SP2, 8 / 8.1, Windows 10, Server 2008 SP2 / R2 SP1, Server 2012 / R2, Server 2016.

集成以下环境：

Apache 2.4.29 Win64

PHP 5.6 (5.6.32) VC11 x64 Thread Safe

MariaDB 10.2 Series

FileZilla Server 0.9.60

Adminer 4.6.2

Caddy v0.10.12_windows_amd64_custom_personal with hook.service plugin

Syncthing windows-amd64-v0.14.46

## 下载
https://github.com/mingfunwong/AndyPHP/archive/master.zip

## 提示
1. 启动 Apache 需要系统安装有 VC15 环境，可到 tool/DirectX Repair V3.5/DirectX_Repair_win8_win10.exe 一键安装。

2. MySQL 账号： root 密码：空 ，建议访问 http://127.0.0.1/reset_mysql.php 重设密码。

3. 虚拟主机编辑 账号：admin 密码：admin

## 使用方法

运行 start 即可启动 Apache 和 MySQL 服务。

运行 ftp_start 启动 FTP 服务。

## 修改虚拟主机
访问 http://127.0.0.1/vhost.php 可在线编辑。

## 一键安装包制作方法备忘录
```
Apache：
1. 到 https://www.apachelounge.com/download/ 下载 Apache 2.x.xx Win64 版，解压放到目录里，命名为 apache
2. 编辑 apahce\conf\httpd.conf
2.1.1 ServerRoot "c:/Apache24" 前面加入 # 号
2.2.5 修改 DocumentRoot "c:/Apache24/htdocs" 前面加入 # 号

2.3. 删除
<Directory />
    AllowOverride none
    Require all denied
</Directory>
2.4. 最后在底部加入
LogFormat "%V %U %b" count
CustomLog "|bin/rotatelogs.exe logs/access_%Y%m%d.log 86400 480" count
LoadModule ratelimit_module modules/mod_ratelimit.so
LoadModule deflate_module modules/mod_deflate.so
LoadModule filter_module modules/mod_filter.so
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_http_module modules/mod_proxy_http.so
LoadModule headers_module modules/mod_headers.so
HttpProtocolOptions unsafe
ServerName localhost:80
AddType application/x-httpd-php .php
LoadModule php5_module ../php/php5apache2_4.dll
PHPIniDir ../php
Include conf/vhost/*.conf
<IfModule mod_ratelimit.c>
    SetOutputFilter RATE_LIMIT
    SetEnv rate-limit 100
</IfModule>

2.5 在 apache\conf\ 新建目录 vhost ，新建文件 00000.default.conf 写入以下内容
<VirtualHost *:80>
DocumentRoot ../web/default/public_html
</VirtualHost>
<Directory ../web/default>
    Options FollowSymLinks
    DirectoryIndex index.php index.html
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>

3. 删除 apache\manual 、 apache\icons 目录

PHP：
1. 到 http://windows.php.net/download/ 下载 VC11 x64 Thread Safe 版，解压放到目录里，命名为 php
2. php.ini-development 复制到 php.ini
3. 修改文件 php.ini，把下面配置粘贴到最后
extension=php_bz2.dll
extension=php_curl.dll
extension=php_fileinfo.dll
extension=php_gd2.dll
extension=php_gettext.dll
extension=php_mbstring.dll
extension=php_exif.dll
extension=php_mysql.dll
extension=php_mysqli.dll
extension=php_openssl.dll
extension=php_pdo_mysql.dll
extension=php_pdo_sqlite.dll
extension_dir = "../php/ext"
date.timezone = Asia/Shanghai
upload_tmp_dir = "../temp"
always_populate_raw_post_data = -1
post_max_size = 20M
upload_max_filesize = 20M

4. libssh2.dll 复制到 Apache24\bin 目录。

MariaDB：
1. 到 https://downloads.mariadb.org/ 下载 MariaDB 10.x Series 版，解压放到目录里，命名为 mysql
2. 复制 my-medium.ini 为 my.ini
3. 删除 mysql\mysql-test 、 mysql\sql-bench 目录

FileZilla Server：
1. 到 https://filezilla-project.org/download.php?type=server 下载安装到目录，命名为 ftp

Adminer：
1. 到 https://www.adminer.org/#download 下载 Adminer 4.x.x 版，放到 web\default 目录里

Caddy：
1. 到 https://caddyserver.com/download 下载，勾选 hook.service 插件

Syncthing：
1. 到 https://github.com/syncthing/syncthing/releases/latest 下载 syncthing-windows-amd64-v0.xx.xx.zip

```