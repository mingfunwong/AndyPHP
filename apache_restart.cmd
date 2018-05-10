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

apache\bin\httpd -t
(sc query .apache | find ".apache">nul && net stop .apache)
(sc query .apache | find ".apache">nul && net start .apache)

echo [Success] Apache restart completed.
pause