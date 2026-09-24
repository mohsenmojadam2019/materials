FROM php:8.4-cli

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

COPY . .
RUN composer dump-autoload --optimize && php artisan package:discover --ansi

EXPOSE 8012
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8012"]
