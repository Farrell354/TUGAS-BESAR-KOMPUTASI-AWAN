# GANTI: Gunakan 'apache' bukan 'cli'. Ini standar production untuk Laravel di Azure.
FROM php:8.3-apache

WORKDIR /var/www/html

# EDIT: Saya tambahkan 'openssh-server' (agar fitur SSH Azure jalan) 
# dan mengaktifkan 'a2enmod rewrite' (agar Routing Laravel jalan/tidak 404)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    openssh-server \
    && docker-php-ext-install pdo pdo_mysql zip mbstring \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# TAMBAHAN PENTING: Setting Apache agar membaca folder /public (bukan root)
# Tanpa ini, website Anda akan menampilkan struktur folder, bukan aplikasinya.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# (BAGIAN INI TETAP SAMA SEPERTI PUNYA ANDA)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# (TETAP SAMA)
COPY . .

# 4. BUILD ASSETS (BAGIAN YANG HILANG SEBELUMNYA)
# Ini akan membuat folder public/build yang berisi CSS/JS
RUN npm install
RUN npm run build

# (TETAP SAMA)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# TAMBAHAN BARU: Mengambil script entrypoint.sh yang sudah kita buat
# Pastikan file 'entrypoint.sh' ada di folder project Anda (selevel dengan Dockerfile)
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# GANTI: Apache menggunakan port 80 secara default (Azure menyukai port 80)
EXPOSE 80

# GANTI: CMD diganti ENTRYPOINT untuk menjalankan script setup + server
ENTRYPOINT ["entrypoint.sh"]
