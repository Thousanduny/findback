FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
COPY public/favicon.png /var/www/html/public/favicon.png
WORKDIR /var/www/html

# Удаляем старый кэш конфигурации, если он есть
RUN rm -f bootstrap/cache/config.php

RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# Перед запуском ОБЯЗАТЕЛЬНО чистим кэш конфигурации
CMD php artisan storage:link && php artisan config:clear && php artisan migrate --force && php artisan db:seed --class=CategorySeeder --force && php artisan db:seed --class=LocationSeeder --force && php artisan serve --host=0.0.0.0 --port=10000
