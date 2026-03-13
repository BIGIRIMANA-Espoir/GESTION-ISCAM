FROM richarvey/nginx-php-fpm:3.1.6

# On copie tout le contenu vers le dossier racine du serveur
COPY . /var/www/html/

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Commande magique pour lancer les migrations au démarrage
RUN php artisan migrate --force

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
