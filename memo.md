## 一键安装包制作方法备忘录

```
Apache：
1. 到 https://www.apachelounge.com/download/ 下载 Apache 2.x.xx Win64 版，解压放到目录里，命名为 apache
2. 编辑 apahce\conf\httpd.conf
2.1.1 Define SRVROOT "c:/Apache24" 改为当前目录，如 C:/AndyPHP/apache
2.1.2 Listen 80 前面加入 # 号
2.2.5 修改 DocumentRoot "${SRVROOT}/htdocs" 前面加入 # 号

2.3. 修改前面加入 # 号
<Directory />
    AllowOverride none
    Require all denied
</Directory>
2.4. 最后在底部加入
Listen 80
LogFormat "%V %U %b" count
CustomLog "|bin/rotatelogs.exe logs/access_%Y%m%d.log 86400 480" count
LoadModule filter_module modules/mod_filter.so
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_http_module modules/mod_proxy_http.so
LoadModule headers_module modules/mod_headers.so
LoadModule access_compat_module modules/mod_access_compat.so
HttpProtocolOptions unsafe
ServerName localhost:80
AddType application/x-httpd-php .php
LoadModule php_module ../php/php8apache2_4.dll
PHPIniDir ../php
Include conf/vhost/*.conf


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

3. 删除 apache\icons 目录

PHP：
1. 到 http://windows.php.net/download/ 下载 VC11 x64 Thread Safe 版，解压放到目录里，命名为 php
2. php.ini-development 复制到 php.ini
3. 修改文件 php.ini，把下面配置粘贴到最后
extension=bz2
extension=ldap
extension=curl
extension=ffi
extension=ftp
extension=fileinfo
extension=gd
extension=gettext
extension=gmp
extension=intl
extension=imap
extension=mbstring
extension=exif
extension=mysqli
extension=odbc
extension=openssl
extension=pdo_mysql
extension=pdo_odbc
extension=pdo_pgsql
extension=pdo_sqlite
extension=pgsql
extension=shmop
extension=soap
extension=sockets
extension=sodium
extension=sqlite3
extension=tidy
extension=xsl
zend_extension=opcache
extension_dir = "../php/ext"
date.timezone = Asia/Shanghai
upload_tmp_dir = "../temp"
post_max_size = 2000M
upload_max_filesize = 2000M
; https://curl.se/docs/caextract.html
[curl]
curl.cainfo="C:\AndyPHP\php\cacert.pem"
[openssl]
openssl.cafile="C:\AndyPHP\php\cacert.pem"

4. libssh2.dll 复制到 Apache24\bin 目录。

MySQL：
到 https://dev.mysql.com/downloads/mysql/ 下载
新建文件 my.ini
运行命令
cd mysql\bin
mysqld --initialize --console
mysqld
(打开新命令行窗口)
mysql -u root –p
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';

删除文件 mysql\bin\mysqld.pdb, mysql\bin\libprotobuf-debug.pdb

FileZilla Server：
1. 到 https://filezilla-project.org/download.php?type=server 下载安装到目录，命名为 ftp

Adminer：
1. 到 https://www.adminer.org/#download 下载 Adminer 4.x.x 版，放到 web\default 目录里

```
