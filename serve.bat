@echo off
REM Start Laravel development server with custom PHP configuration
echo Starting UNHUSHED server with increased upload limits (20MB)...
php -c php-custom.ini artisan serve
