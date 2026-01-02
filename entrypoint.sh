#!/bin/bash

# --- BAGIAN 1: PERSIAPAN FOLDER ---
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# --- BAGIAN 2: PERMISSION ---
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# =================================================================
# --- [UPDATE] BAGIAN 2.5: SETTING URL BARU ---
# =================================================================
# Ini memaksa CSS dan Gambar menggunakan domain baru kamu
export APP_URL="https://tambalfinderr.azurewebsites.net"
export ASSET_URL="https://tambalfinderr.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 3: PEMBERSIHAN & CACHING ---
cd /var/www/html

# Bersihkan cache lama (biar gak nyangkut di URL lama)
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Simpan konfigurasi baru secara permanen
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 4: SETUP APLIKASI ---
php artisan storage:link

# Migrasi otomatis (Hapus tanda pagar jika sudah yakin database aman)
# php artisan migrate --force

# --- BAGIAN 5: START SERVER ---
service ssh start
apache2-foreground
