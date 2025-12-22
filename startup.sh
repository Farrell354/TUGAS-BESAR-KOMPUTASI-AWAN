#!/bin/bash

# Salin config nginx default ke backup
cp /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak

# Update root folder ke /public (Wajib untuk Laravel)
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default

# Tambahkan index.php ke daftar index
sed -i 's|index index.php index.html index.htm;|index index.php index.html index.htm;|g' /etc/nginx/sites-available/default

# Konfigurasi handling URL Laravel (try_files)
sed -i 's|try_files $uri $uri/ =404;|try_files $uri $uri/ /index.php?$args;|g' /etc/nginx/sites-available/default

# Restart Nginx untuk menerapkan perubahan
service nginx reload

# Jalankan PHP-FPM (command utama container)
php-fpm
