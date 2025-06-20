# Apache bilan PHP
FROM php:8.2-apache

# rewrite moduli
RUN a2enmod rewrite

# loyihani rootga ko‘chir
COPY . /var/www/html/

# ✅ POSTGRES uchun extension o‘rnat
RUN docker-php-ext-install pdo pdo_pgsql

EXPOSE 80
