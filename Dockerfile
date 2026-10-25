FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
WORKDIR /var/www/html

# Удаляем старый кэш конфигурации, если он есть
RUN rm -f bootstrap/cache/config.php

RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# Перед запуском ОБЯЗАТЕЛЬНО чистим кэш конфигурации
CMD echo "APP_ENV=production" > .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_KEY=base64:w5/jXCxTBOJ/37+qbyTrRtYfZxfZx5mw6cTEQ1Nu8+0=" >> .env && \
    echo "DB_CONNECTION=pgsql" >> .env && \
    echo "DB_HOST=dpg-dbeqtpgae00c73e24c8g-a.frankfurt-postgres.render.com" >> .env && \
    echo "DB_PORT=5432" >> .env && \
    echo "DB_DATABASE=findback_db_ttll" >> .env && \
    echo "DB_USERNAME=findback_db_ttll_user" >> .env && \
    echo "DB_PASSWORD=rBCPezsTfWByns5EtK1x5wyya1EqI1vX" >> .env && \
    echo "DATABASE_URL=postgresql://findback_db_ttll_user:rBCPezsTfWByns5EtK1x5wyya1EqI1vX@dpg-dbeqtpgae00c73e24c8g-a.frankfurt-postgres.render.com/findback_db_ttll" >> .env && \
    php artisan config:clear && \
    php artisan migrate --seed --force && \
    php artisan serve --host=0.0.0.0 --port=10000
