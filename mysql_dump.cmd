@ECHO OFF
cd /D %~dp0

mysql\bin\mysqldump -uroot -p --all-databases > www\sql.sql

echo [Success] Dump completed.
pause