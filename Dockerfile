FROM php:8.3-apache-bullseye

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libgmp-dev \
    libzip-dev \
    default-mysql-client

RUN docker-php-ext-install pdo pdo_pgsql gd zip pdo_mysql mysqli gmp

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY default.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80

RUN a2enmod rewrite
CMD ["apache2-foreground"]
