@echo off
echo ========================================
echo Fix Database - Add Missing Tables and Columns
echo ========================================
echo.

REM Set MySQL connection details from app_local.php
set MYSQL_HOST=localhost
set MYSQL_PORT=3307
set MYSQL_USER=root
set MYSQL_PASSWORD=
set MYSQL_DATABASE=mneylink

echo Connecting to MySQL database: %MYSQL_DATABASE%
echo Port: %MYSQL_PORT%
echo.

REM Path to MySQL binary (adjust if needed)
set MYSQL_BIN=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe

REM Check if MySQL binary exists
if not exist "%MYSQL_BIN%" (
    echo MySQL binary not found at: %MYSQL_BIN%
    echo Please update the MYSQL_BIN variable in this script.
    echo.
    echo Trying to use mysql from PATH...
    set MYSQL_BIN=mysql
)

echo Running SQL script: create_missing_tables.sql
echo.

"%MYSQL_BIN%" -h %MYSQL_HOST% -P %MYSQL_PORT% -u %MYSQL_USER% %MYSQL_DATABASE% < create_missing_tables.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo SUCCESS! Database updated successfully.
    echo ========================================
    echo.
    echo The following changes have been made:
    echo - Created buyer_reports table
    echo - Created member_reports table  
    echo - Added device column to traffics table
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR! Failed to update database.
    echo ========================================
    echo.
    echo Please check:
    echo 1. MySQL service is running
    echo 2. Database credentials are correct
    echo 3. Database 'mneylink' exists
    echo.
)

pause


