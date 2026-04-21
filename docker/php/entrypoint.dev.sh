#!/bin/sh
set -e

ROLE="${1:-app}"

cd /var/www

mkdir -p \
    bootstrap/cache \
    storage/app/backup-temp \
    storage/app/private/GNAIbackups \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ "$ROLE" = "app" ]; then
    if [ ! -f vendor/autoload.php ]; then
        composer install --prefer-dist --no-interaction --no-scripts
        php artisan package:discover --ansi
    fi
else
    while [ ! -f vendor/autoload.php ]; do
        echo "Aguardando dependencias PHP em vendor/..."
        sleep 2
    done
fi

if [ "$ROLE" = "app" ]; then
    php artisan storage:link --force || true
    php artisan migrate --force
    exec php-fpm
fi

if [ "$ROLE" = "scheduler" ]; then
    exec php artisan schedule:work
fi

exec "$@"
