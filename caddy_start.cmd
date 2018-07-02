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
call :start_caddy

echo [Success] Installation completed.
pause
goto :eof

:start_caddy
caddy\caddy -service install -conf %cd%\caddy\Caddyfile -agree
caddy\caddy -service start
goto :eof

:stop_caddy
caddy\caddy -service stop
caddy\caddy -service uninstall
goto :eof