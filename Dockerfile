# 1️⃣ PHP + Apache image
FROM php:8.2-apache

# 2️⃣ Apache rewrite moduli
RUN a2enmod rewrite

# 3️⃣ POSTGRES header fayllari uchun libpq-dev o‘rnatish
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# 4️⃣ Loyihani Apache rootga ko‘chirish
COPY . /var/www/html/

# 5️⃣ Apache avtomatik 80-portni ochadi
EXPOSE 80
