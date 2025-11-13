@echo off
echo ========================================
echo   RESTARTING LARAGON APACHE
echo ========================================
echo.

echo Stopping Apache...
net stop Apache 2>nul
taskkill /F /IM httpd.exe 2>nul

timeout /t 2 /nobreak >nul

echo Starting Apache...
net start Apache 2>nul

echo.
echo ========================================
echo   APACHE RESTARTED!
echo ========================================
echo.
echo Now refresh your browser with Ctrl+F5
echo.
pause

