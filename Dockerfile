FROM php:8.4.21RC1-zts-trixie
# Installer extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
 libicu-dev libzip-dev unzip git \
 && docker-php-ext-install pdo pdo_mysql intl zip \
 && rm -rf /var/lib/apt/lists/*


RUN a2enmod rewrite
WORKDIR /var/www/html