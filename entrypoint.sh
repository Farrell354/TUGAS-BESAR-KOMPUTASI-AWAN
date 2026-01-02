#!/bin/bash

# --- BAGIAN 1: PERSIAPAN FOLDER ---
# Membuat folder storage & cache yang sering hilang saat deploy
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# --- BAGIAN 2: PERMISSION ---
# Memberikan akses tulis ke www-data (User Apache)
# Ini PENTING agar tidak muncul error "The stream or file could not be opened: failed to open stream: Permission denied"
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- BAGIAN 3: PEMBERSIHAN CACHE (WAJIB DI-UNCOMMENT) ---
# SAYA AKTIFKAN: Ini sangat penting di Azure agar perubahan Environment Variable (APP_URL, Database) terbaca.
cd /var/www/html
php artisan optimize:clear
php artisan config:clear
php artisan view:clear

# --- BAGIAN 4: SETUP APLIKASI ---
# Membuat symlink agar gambar bisa diakses publik
php artisan storage:link

# Migrasi Otomatis (Opsional)
# Jika Anda ingin migrasi jalan otomatis setiap deploy, hapus tanda pagar (#) di bawah ini:
# php artisan migrate --force

# --- BAGIAN 5: START SERVER ---
# Menjalankan SSH agar fitur SSH di Azure Portal bisa dipakai
service ssh start

# Menjalankan Apache di foreground (agar container tidak mati)
apache2-foreground
