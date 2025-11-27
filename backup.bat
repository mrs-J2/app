@echo off
REM --- Use PowerShell for a more reliable timestamp on Windows ---
FOR /F "tokens=*" %%i IN ('powershell -Command "Get-Date -Format 'yyyyMMdd_HHmmss'"') DO SET "TIMESTAMP=%%i"

REM --- Define DB connection details ---
SET DB_USER=laravel
SET DB_PASS=secret
SET DB_NAME=school_portal
SET SERVICE_NAME=mysql

REM --- Create the host backups folder if it doesn't exist ---
if not exist backups mkdir backups

REM --- NEW COMMAND: Perform DUMP AND GZIP inside the MySQL container ---
REM The output is saved to STDOUT, which is then redirected to the host file.
docker-compose exec -T %SERVICE_NAME% sh -c "mysqldump -u %DB_USER% -p%DB_PASS% %DB_NAME% | gzip" > backups\backup_%TIMESTAMP%.sql.gz

echo.
echo BACKUP CREATED : backups\backup_%TIMESTAMP%.sql.gz
echo.