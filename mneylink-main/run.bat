@echo off
echo ========================================
echo  MneyLink - Quick Start
echo ========================================
echo.

echo Checking PHP version...
php -v | findstr /C:"7.4" >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP 7.4 is required!
    echo Current PHP version:
    php -v
    echo.
    echo Please switch to PHP 7.4 in Laragon:
    echo 1. Right-click Laragon tray icon
    echo 2. Menu ^> PHP ^> php-7.4.x
    echo 3. Restart Laragon
    echo.
    pause
    exit /b 1
)

echo [OK] PHP 7.4 detected!
echo.

echo Creating directories...
if not exist "tmp\cache\models" mkdir tmp\cache\models
if not exist "tmp\cache\persistent" mkdir tmp\cache\persistent
if not exist "tmp\cache\views" mkdir tmp\cache\views
if not exist "tmp\cache\proxy" mkdir tmp\cache\proxy
if not exist "tmp\sessions" mkdir tmp\sessions
if not exist "tmp\tests" mkdir tmp\tests
if not exist "logs" mkdir logs
echo.

echo Creating database...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS mneylink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if errorlevel 1 (
    echo [WARNING] Could not create database automatically.
    echo Please create database manually in MySQL:
    echo   CREATE DATABASE mneylink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    echo.
) else (
    echo [OK] Database created!
)
echo.

echo Running migrations...
bin\cake migrations migrate
echo.

echo ========================================
echo  Setup completed!
echo ========================================
echo.
echo Starting development server...
echo Access your app at: http://localhost:8080
echo.
echo Press Ctrl+C to stop the server.
echo.

bin\cake server -p 8080


