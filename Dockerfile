FROM php:8.1-apache

# Installation des dépendances système et PHP pour les PDF
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

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du Document Root Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Configuration du répertoire pour autoriser le suivi des liens symboliques
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copie du code source
COPY . .

# Préparation de l'environnement
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN composer install --no-interaction --prefer-dist
RUN php artisan key:generate --force

# Création des dossiers de stockage avec les bons droits
RUN mkdir -p storage/app/public/factures \
    && mkdir -p storage/app/public/burgers \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Création du lien symbolique forcé
RUN php artisan storage:link --force

EXPOSE 80
CMD ["apache2-foreground"]