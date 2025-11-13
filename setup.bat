@echo off
echo ========================================
echo  MneyLink - Setup Script
echo ========================================
echo.

echo [1/5] Checking PHP version...
php -v
echo.

echo [2/5] Creating database...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS mneylink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo Database 'mneylink' created successfully!
echo.

echo [3/5] Creating tmp directories...
if not exist "tmp\cache\models" mkdir tmp\cache\models
if not exist "tmp\cache\persistent" mkdir tmp\cache\persistent
if not exist "tmp\cache\views" mkdir tmp\cache\views
if not exist "tmp\sessions" mkdir tmp\sessions
if not exist "tmp\tests" mkdir tmp\tests
if not exist "logs" mkdir logs
echo Directories created successfully!
echo.

echo [4/5] Setting permissions...
icacls tmp /grant Everyone:(OI)(CI)F /T
icacls logs /grant Everyone:(OI)(CI)F /T
echo Permissions set successfully!
echo.

echo [5/5] Running database migrations...
bin\cake migrations migrate
echo.

echo ========================================
echo  Setup completed successfully!
echo ========================================
echo.
echo Next steps:
echo 1. Start Laragon
echo 2. Access: http://mneylink-main.test
echo    Or run: bin\cake server -p 8080
echo.
pause



