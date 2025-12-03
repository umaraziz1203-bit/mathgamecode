#!/bin/bash

echo "========================================"
echo "Le Math Game - Database Setup"
echo "========================================"
echo ""
echo "This script will create the database and import sample data."
echo "Make sure MySQL is running!"
echo ""

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SQL_FILE="$SCRIPT_DIR/database.sql"

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "ERROR: database.sql not found!"
    exit 1
fi

# Prompt for MySQL root password
read -sp "Enter MySQL root password (press Enter if none): " MYSQL_PASS
echo ""

if [ -z "$MYSQL_PASS" ]; then
    mysql -u root < "$SQL_FILE"
else
    mysql -u root -p"$MYSQL_PASS" < "$SQL_FILE"
fi

if [ $? -eq 0 ]; then
    echo ""
    echo "========================================"
    echo "Database setup completed successfully!"
    echo "========================================"
    echo ""
    echo "Default login credentials:"
    echo "Username: admin"
    echo "Password: password"
    echo ""
else
    echo ""
    echo "========================================"
    echo "ERROR: Database setup failed!"
    echo "========================================"
    echo ""
    echo "Please check:"
    echo "1. MySQL is running"
    echo "2. MySQL root password is correct"
    echo "3. You have proper permissions"
    echo ""
    exit 1
fi

