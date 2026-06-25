FROM php:8.2-cli-alpine

WORKDIR /app

COPY . .

CMD php artisan serve --host=0.0.0.0 --port=8000