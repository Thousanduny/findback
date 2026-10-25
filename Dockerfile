FROM richarvey/nginx-php-fpm:php8.4

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

CMD composer install --no-dev --optimize-autoloader && php artisan migrate --seed --force && /start.sh
