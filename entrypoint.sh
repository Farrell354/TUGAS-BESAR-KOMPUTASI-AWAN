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
# --- BAGIAN 2.5: SETTING URL BARU ---
# =================================================================
# Export variabel ini AGAR TERBACA oleh perintah cache di bawah
export APP_URL="https://tambalfinderr.azurewebsites.net"
export ASSET_URL="https://tambalfinderr.azurewebsites.net"
export APP_ENV=production
export SCHEME=https

# --- BAGIAN 3: PEMBERSIHAN & CACHING ---
cd /var/www/html

# [UPDATE] Hard Clear Cache (Sesuai Request)
# Menghapus paksa file cache fisik supaya tidak ada konfigurasi lama yang nyangkut
echo "Hard clearing cache files..."
php artisan optimize:clear || true
rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php bootstrap/cache/packages.php bootstrap/cache/services.php || true

# Bersihkan sisa-sisa cache via artisan
php artisan config:clear
php artisan view:clear
php artisan route:clear

# [PENTING] Build ulang cache dengan URL baru
# Karena cache lama sudah dihapus manual di atas, perintah ini akan
# membuat file cache baru yang sudah berisi URL "tambalfinderr..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- BAGIAN 4: SETUP APLIKASI ---
php artisan storage:link

# Migrasi otomatis (Uncomment jika berani otomatis)
# php artisan migrate --force

# --- BAGIAN 5: START SERVER ---
service ssh start
apache2-foreground
