# PHP va Apache
FROM php:8.2-apache
RUN a2enmod rewrite
COPY . /var/www/html/
RUN docker-php-ext-install pdo pdo_pgsql
EXPOSE 80