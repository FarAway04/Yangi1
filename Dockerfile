# PHP 8.1 image-ni olamiz
FROM php:8.1-cli

# cURL extension zarur bo‘ladi
RUN docker-php-ext-install pdo pdo_mysql

# cURL va boshqa utilitlarni o‘rnatamiz
RUN apt-get update && apt-get install -y \
    curl \
    unzip

# Loyihani konteynerga nusxalaymiz
COPY . /app

# Loyihani ishchi katalog sifatida belgilaymiz
WORKDIR /app

# Boshlanish komandasi
CMD ["php", "index.php"]
