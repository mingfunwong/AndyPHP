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

call :stop_apache
call :stop_mysql
echo [Success] Uninstall completed.
pause
goto :eof

:start_apache
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
