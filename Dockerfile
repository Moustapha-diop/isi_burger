FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Autoriser Apache à suivre les liens symboliques
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Sans --no-dev pour inclure dompdf
RUN composer install --no-interaction --prefer-dist

RUN php artisan key:generate --force
RUN php artisan config:clear

# Créer les dossiers nécessaires
RUN mkdir -p storage/app/public/factures
RUN mkdir -p storage/app/public/burgers

# Lien symbolique
RUN php artisan storage:link

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache public/storage
RUN chmod -R 755 storage bootstrap/cache public/storage

EXPOSE 80
CMD ["apache2-foreground"]