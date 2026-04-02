# FROM php:8.1-apache

# # Installation des dépendances pour PostgreSQL
# RUN apt-get update && apt-get install -y \
#     libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip git curl \
#     && docker-php-ext-install pdo pdo_pgsql mbstring gd \
#     && apt-get clean

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
#     /etc/apache2/sites-available/*.conf
# RUN a2enmod rewrite

# WORKDIR /var/www/html
# COPY . .

# RUN composer install --no-interaction --prefer-dist --no-dev
# RUN cp .env.example .env && php artisan key:generate --force

# RUN chown -R www-data:www-data storage bootstrap/cache

# EXPOSE 80
# CMD ["apache2-foreground"]

FROM php:8.1-apache

# Installation des dépendances pour PostgreSQL
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

# On copie tout (y compris ton .env configuré avec host.docker.internal)
COPY . .

# On installe les dépendances
RUN composer install --no-interaction --prefer-dist --no-dev

# ON NE FAIT PAS de "cp .env.example .env" ici pour ne pas écraser tes réglages
RUN php artisan key:generate --force
# Ajoute cette ligne pour être SÛR que le cache est vidé
RUN php artisan config:clear

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]