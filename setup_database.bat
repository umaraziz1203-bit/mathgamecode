@echo off
echo ========================================
echo Le Math Game - Database Setup
echo ========================================
echo.
echo This script will create the database and import sample data.
echo Make sure XAMPP MySQL is running!
echo.
pause

REM Set MySQL path (adjust if your XAMPP is in a different location)
set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe

REM Check if MySQL exists
if not exist "%MYSQL_PATH%" (
    echo ERROR: MySQL not found at %MYSQL_PATH%
    echo Please update the MYSQL_PATH in this script to match your XAMPP installation.
    pause
    exit /b 1
)

echo.
echo Creating database and importing data...
echo.

REM Import the SQL file
"%MYSQL_PATH%" -u root -e "source %~dp0database.sql"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Database setup completed successfully!
    echo ========================================
    echo.
    echo Default login credentials:
    echo Username: admin
    echo Password: password
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR: Database setup failed!
    echo ========================================
    echo.
    echo Please check:
    echo 1. XAMPP MySQL is running
    echo 2. MySQL root password is empty (default)
    echo 3. You have proper permissions
    echo.
)

pause

