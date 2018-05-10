@ECHO OFF
cd /D %~dp0

apache\bin\httpd -t

pause