# 1. On prend une image Linux officielle avec PHP 8.4 et Apache
FROM php:8.4-apache

# 2. On installe les outils système indispensables et l'extension pour MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql \
    && a2enmod rewrite

# 3. On configure Apache pour qu'il pointe sur le dossier "public" de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's!Options FollowSymLinks!Options Indexes FollowSymLinks!g' /etc/apache2/apache2.conf


# 4. On installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. On copie tout le code de ton projet dans le serveur
WORKDIR /var/www/html
COPY . .

# 6. On installe les dépendances Symfony pour la PRODUCTION
ENV APP_ENV=prod
RUN composer install --optimize-autoloader --ignore-platform-reqs

# 7. Préparation initiale des dossiers
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var public assets

# 8. Script d'entrée robuste qui nettoie et donne les accès totaux au DÉMARRAGE réel
RUN echo '#!/bin/sh' > /usr/local/bin/docker-entrypoint.sh \
    && echo 'rm -rf var/cache/*' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'mkdir -p var/cache/prod var/log && chmod -R 777 var' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'chown -R www-data:www-data var' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration' >> /usr/local/bin/docker-entrypoint.sh \
    && echo 'exec apache2-foreground' >> /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

