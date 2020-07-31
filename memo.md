## 一键安装包制作方法备忘录
```
Apache：
1. 到 https://www.apachelounge.com/download/ 下载 Apache 2.x.xx Win64 版，解压放到目录里，命名为 apache
2. 编辑 apahce\conf\httpd.conf
2.1.1 ServerRoot "c:/Apache24" 前面加入 # 号
2.1.2 Listen 80 前面加入 # 号
2.2.5 修改 DocumentRoot "c:/Apache24/htdocs" 前面加入 # 号

2.3. 删除
<Directory />
    AllowOverride none
    Require all denied
</Directory>
2.4. 最后在底部加入
Listen 801
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
<VirtualHost *:801>
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
4. 打开 my.ini 在 [mysqld] 后面加入：
innodb_buffer_pool_size = 256M
innodb_log_file_size = 256M
innodb_thread_concurrency = 16
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = normal

FileZilla Server：
1. 到 https://filezilla-project.org/download.php?type=server 下载安装到目录，命名为 ftp

Adminer：
1. 到 https://www.adminer.org/#download 下载 Adminer 4.x.x 版，放到 web\default 目录里

Caddy：
1. 到 https://caddyserver.com/download 下载，勾选 hook.service 插件

```
