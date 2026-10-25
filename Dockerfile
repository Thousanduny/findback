FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# ПРИНУДИТЕЛЬНО перезаписываем переменные окружения прямо перед запуском
CMD export DB_HOST=dpg-dbeqtpgae00c73e24c8g-a.frankfurt-postgres.render.com && \
    export DB_DATABASE=findback_db_ttll && \
    export DB_USERNAME=findback_db_ttll_user && \
    export DB_PASSWORD=rBCPezsTfWByns5EtK1x5wyya1EqI1vX && \
    php artisan config:clear && \
    php artisan migrate --seed --force && \
    php artisan serve --host=0.0.0.0 --port=8000
