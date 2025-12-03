# CLI Database Setup Guide

## Quick Setup Using Command Line

### Windows (Command Prompt or PowerShell)

1. **Open Command Prompt or PowerShell**

2. **Navigate to XAMPP MySQL bin directory:**
   ```cmd
   cd C:\xampp\mysql\bin
   ```

3. **Run the SQL file:**
   ```cmd
   mysql -u root -e "source C:\xampp\htdocs\Le_Math Game\database.sql"
   ```

   Or if you have a MySQL password:
   ```cmd
   mysql -u root -p -e "source C:\xampp\htdocs\Le_Math Game\database.sql"
   ```

### Alternative: Direct MySQL Command

1. **Open Command Prompt**

2. **Navigate to your project directory:**
   ```cmd
   cd "C:\xampp\htdocs\Le_Math Game"
   ```

3. **Run MySQL:**
   ```cmd
   C:\xampp\mysql\bin\mysql.exe -u root < database.sql
   ```

   Or with password:
   ```cmd
   C:\xampp\mysql\bin\mysql.exe -u root -p < database.sql
   ```

### Using the Batch Script (Easiest)

Simply double-click `setup_database.bat` in your project folder.

## Verify Database Creation

After running the setup, verify the database was created:

```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "SHOW DATABASES;"
```

You should see `le_math_game` in the list.

## Verify Tables

Check that all tables were created:

```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "USE le_math_game; SHOW TABLES;"
```

You should see:
- users
- logic_puzzles
- mathematics_puzzles
- memory_animals
- game_progress

## Verify Sample Data

Check that puzzles were inserted:

```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "USE le_math_game; SELECT COUNT(*) FROM logic_puzzles;"
```

Should return: 10

```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "USE le_math_game; SELECT COUNT(*) FROM mathematics_puzzles;"
```

Should return: 10

```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "USE le_math_game; SELECT COUNT(*) FROM memory_animals;"
```

Should return: 10

## Troubleshooting

### Error: 'mysql' is not recognized
- Make sure you're in the XAMPP MySQL bin directory, OR
- Use the full path: `C:\xampp\mysql\bin\mysql.exe`

### Error: Access denied
- Check if MySQL is running in XAMPP
- Verify root password (or leave empty if default)
- Try: `mysql -u root -p` and enter password when prompted

### Error: Can't connect to MySQL server
- Start MySQL service in XAMPP Control Panel
- Check if MySQL is running on port 3306

## Manual SQL Execution

If CLI doesn't work, you can also:

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click on "SQL" tab
3. Copy and paste contents of `database.sql`
4. Click "Go"

