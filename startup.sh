#!/bin/bash

# 1. Backup config asli
cp /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak

# 2. Arahkan root ke folder public
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default

# 3. Tambahkan index.php
sed -i 's|index index.php index.html index.htm;|index index.php index.html index.htm;|g' /etc/nginx/sites-available/default

# 4. FIX ROUTING (Ini yang bikin /register bisa jalan)
sed -i 's|try_files $uri $uri/ =404;|try_files $uri $uri/ /index.php?$args;|g' /etc/nginx/sites-available/default

# 5. Restart Nginx
service nginx reload

# 6. Jalankan PHP
php-fpm
