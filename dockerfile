FROM php:8.1-fpm

   # Установка необходимых пакетов и расширений
   RUN apt-get update && apt-get install -y \
           libzip-dev \
           unzip \
           git \
           curl \
           libpng-dev \
           libjpeg-dev \
           libfreetype6-dev \
       && docker-php-ext-configure gd --with-freetype --with-jpeg \
       && docker-php-ext-install gd zip pdo pdo_mysql \
       && apt-get clean

   # Установка Symfony CLI
   RUN curl -sS https://get.symfony.com/cli/installer | bash && \
       mv /root/.symfony*/bin/symfony /usr/local/bin/symfony && \
       rm -rf /root/.symfony*

   # Установка Composer
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

   # Добавляем пользователя
   RUN useradd -m symfonyuser
   USER symfonyuser

   # Установка рабочей директории
   WORKDIR /var/www/html

   # Копируем файлы в контейнер
   COPY --chown=symfonyuser:symfonyuser . .

   # Установка зависимостей
   RUN composer install

   RUN composer require doctrine/doctrine-migrations-bundle

   RUN composer update

   # Открываем порт 9000 для PHP-FPM
   EXPOSE 8000

   # Запускаем PHP-FPM
   CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]