FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-interaction --no-scripts --optimize-autoloader

FROM php:8.2-cli-alpine

RUN apk add --no-cache $PHPIZE_DEPS sqlite-dev && docker-php-ext-install pdo_sqlite && apk del $PHPIZE_DEPS

WORKDIR /app

COPY . .

COPY --from=vendor /app/vendor /app/vendor

RUN mkdir -p database && touch database/database.sqlite

EXPOSE 8000

CMD ["sh", "-c", "test -f .env || cp .env.example .env; grep -q '^APP_KEY=base64:' .env || php artisan key:generate --force; php artisan package:discover --ansi; php artisan migrate --force; php artisan l5-swagger:generate; exec php artisan serve --host=0.0.0.0 --port=8000"]
