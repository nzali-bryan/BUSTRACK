# 1. On prend une image Linux officielle avec PHP 8.2 et le serveur Web Apache
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

# 4. On installe Composer (le gestionnaire de paquets de PHP) à l'intérieur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. On copie tout le code de ton projet dans le serveur
WORKDIR /var/www/html
COPY . .

# 6. On installe les dépendances de ton projet Symfony
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs


# 7. On donne les bonnes permissions d'accès aux dossiers de cache et de logs
RUN chown -R www-data:www-data var public

# 8. AUTOMATION : On force l'exécution des migrations au démarrage du conteneur
RUN printf "#!/bin/sh\nphp bin/console doctrine:migrations:migrate --no-interaction\nexec apache2-foreground\n" > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
