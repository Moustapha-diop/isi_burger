FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --prefer-dist --no-dev
RUN cp .env.example .env && php artisan key:generate --force

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]