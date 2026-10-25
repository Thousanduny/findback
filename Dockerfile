FROM richarvey/nginx-php-fpm:latest

COPY . .

# Разрешаем composer работать от рута
ENV COMPOSER_ALLOW_SUPERUSER 1

# НЕ пропускаем composer (убираем SKIP_COMPOSER), чтобы он установил зависимости
# ENV SKIP_COMPOSER 1  <-- эту строку удалили

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Команда для запуска миграций и старта сайта
CMD composer install --no-dev --optimize-autoloader && php artisan migrate --seed --force && /start.sh
