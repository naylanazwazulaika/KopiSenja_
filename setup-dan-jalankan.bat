@echo off
title KOPI SENJA - Setup & Jalankan
echo ========================================================
echo   KOPI SENJA - Coffee Shop & Pastry Management System
echo   Sistem Otomasi Setup Database & Server
echo ========================================================
echo.
echo [1/3] Memeriksa konfigurasi aplikasi...
if not exist .env (
    copy .env.example .env
    php artisan key:generate
)

echo.
echo [2/3] Menyiapkan Database dan Data Awal (Migrate & Seed)...
echo Pastikan MySQL di XAMPP sudah di-START!
echo.
php artisan migrate --seed --force

echo.
echo [3/3] Menjalankan Server Laravel di http://127.0.0.1:8000 ...
echo Silakan buka browser Anda di: http://127.0.0.1:8000
echo Tekan Ctrl+C untuk menghentikan server.
echo.
php artisan serve --port=8000
pause
