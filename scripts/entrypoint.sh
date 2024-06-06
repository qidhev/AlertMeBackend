#!/bin/sh
set -e # Завершение работы если команда вернула ошибку

echo "Запуск composer"
composer install

echo "Запуск npm"
npm install
npm run build

echo "Ожидание иницализации базы данных"

while ! nc -z "$DB_HOST" $DB_PORT; do
    sleep 1
done

echo "Запуск миграции"
php artisan migrate

echo "Запуск сидеров"

#php artisan db:seed MainSeeder

#exec php-fpm # Прододжение работы контейнера через php-fpm

php artisan serve --port=8080 --host=0.0.0.0
