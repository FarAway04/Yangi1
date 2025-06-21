# Apache bilan PHP 8.2 + PostgreSQL extension
FROM php:8.2-apache

# Rewrite moduli
RUN a2enmod rewrite

# PostgreSQL driver uchun zarur libpq
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Loyihani Apache rootga ko‘chirish
COPY . /var/www/html/

# step/ papkani yarat va ruxsat ber
RUN mkdir -p /var/www/html/step && chmod -R 777 /var/www/html/step

EXPOSE 80
