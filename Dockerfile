# PHP + Apache
FROM php:8.2-apache

RUN a2enmod rewrite

# 🔑 PostgreSQL extension
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# 🔑 Apache rootga loyihani ko‘chirish
COPY . /var/www/html/

# 🔑 step/ papkani och va ruxsat ber
RUN mkdir -p /var/www/html/step && chmod -R 777 /var/www/html/step

EXPOSE 80
