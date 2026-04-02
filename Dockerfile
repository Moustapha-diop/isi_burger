# Utilisation de l'image officielle PHP 8.1 avec Apache
FROM php:8.1-apache

# Installation des dépendances système et des extensions PHP nécessaires
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

# Installation de Composer via l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration d'Apache pour pointer vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie du code source dans le conteneur
COPY . .

# Vérification/Création du fichier .env pour permettre à Laravel de fonctionner
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Installation des dépendances PHP (sans les outils de développement)
RUN composer install --no-interaction --prefer-dist --no-dev

# Génération de la clé et nettoyage du cache pour éviter les erreurs de configuration
RUN php artisan key:generate --force
RUN php artisan config:clear

# Attribution des droits sur les dossiers de stockage et de cache
RUN chown -R www-data:www-data storage bootstrap/cache
# À ajouter dans ton Dockerfile avant l'exposition du port 80
RUN php artisan storage:link

# Exposition du port 80 pour le web
EXPOSE 80
CMD ["apache2-foreground"]