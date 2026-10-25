# Берем официальный образ PHP 8.4 с установленным Composer
FROM php:8.4-cli

# Устанавливаем системные зависимости (нужны для Laravel)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем проект
COPY . /var/www/html
WORKDIR /var/www/html

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Делаем папки storage и cache доступными для записи
RUN chmod -R 777 storage bootstrap/cache

# Открываем порт (Render сам подставит нужный)
EXPOSE 8000

# Запускаем миграции и встроенный сервер PHP
CMD php artisan migrate --seed --force && php artisan serve --host=0.0.0.0 --port=8000
