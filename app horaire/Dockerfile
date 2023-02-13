FROM php:latest

EXPOSE 8000
WORKDIR /PRJ-horaires
RUN apt-get update && apt-get install ruby ruby-dev libmcrypt-dev libonig-dev libzip-dev git zip -y
RUN docker-php-ext-install pdo mbstring zip mysqli pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /PRJ-horaires
RUN cp .env.example.deployment .env
RUN composer install
RUN php artisan key:generate

CMD if [ "$MODE" = "test" ]; then php artisan test; else php artisan serve --host 0.0.0.0; fi
