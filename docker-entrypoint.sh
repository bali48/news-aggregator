#!/bin/sh

until mysqladmin ping -h laravel-db -u"${MYSQL_USER}" -p"${MYSQL_PASSWORD}" --silent; do
  echo 'Waiting for MySQL to be ready...'
  sleep 2
done

php artisan migrate --force

if [ -z "$(php artisan passport:client --personal --quiet)" ]; then
  php artisan passport:install
fi

exec php artisan serve --host=0.0.0.0 --port=8000
