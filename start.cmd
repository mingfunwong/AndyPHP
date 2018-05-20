@ECHO OFF
cd /D %~dp0
set "_FilePath=%~f0"
setlocal EnableExtensions EnableDelayedExpansion
fltmc >nul 2>&1 || (
	echo Set UAC = CreateObject^("Shell.Application"^) > "%temp%\GetAdmin.vbs"
	echo UAC.ShellExecute "!_FilePath!", "", "", "runas", 1 >> "%temp%\GetAdmin.vbs"
	"%temp%\GetAdmin.vbs"
	del /f /q "%temp%\GetAdmin.vbs" >nul 2>&1
	exit
)

call :stop_caddy
call :stop_apache
call :stop_mysql
call :start_caddy
call :start_apache
call :start_mysql
explorer http://127.0.0.1/
echo [Success] Installation completed.
pause
goto :eof

:start_apache
COPY php\libssh2.dll C:\Windows\System32\libssh2.dll
COPY php\libeay32.dll C:\Windows\System32\libeay32.dll
COPY php\ssleay32.dll C:\Windows\System32\ssleay32.dll
apache\bin\httpd -t
apache\bin\httpd -k install -n .apache
net start .apache
goto :eof

:stop_apache
(sc query .apache | find ".apache">nul && net stop .apache)
(sc query .apache | find ".apache">nul && sc delete .apache)
goto :eof

:start_mysql
mysql\bin\mysqld --install .mysql
(sc query .mysql | find ".mysql">nul && net start .mysql)
goto :eof

:stop_mysql
(sc query .mysql | find ".mysql">nul && net stop .mysql)
(sc query .mysql | find ".mysql">nul && sc delete .mysql)
goto :eof

:start_caddy
caddy\caddy -service install -conf="%cd%\caddy\Caddyfile"
caddy\caddy -service start
goto :eof

:stop_caddy
caddy\caddy -service stop
caddy\caddy -service uninstall
goto :eof