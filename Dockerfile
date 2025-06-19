# Apache bilan PHP
FROM php:8.2-apache

# Apache config uchun rewrite moduli
RUN a2enmod rewrite

# Loyihani Apache rootga ko‘chiramiz
COPY . /var/www/html/

# PHP extension (agar kerak bo‘lsa)
RUN docker-php-ext-install pdo pdo_mysql

# Apache avtomatik 80-portni ochadi
EXPOSE 80
