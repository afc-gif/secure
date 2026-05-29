#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

sed -ri "s/Listen 80|Listen 8080/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>|<VirtualHost \*:8080>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ ! -f .env ] && [ "${APP_ENV:-local}" != "production" ]; then
    cp .env.example .env
fi

if [ -z "${APP_KEY:-}" ] && [ -f .env ]; then
    php artisan key:generate --force
fi

php artisan config:clear --quiet || true

if [ "${DB_CONNECTION:-}" = "pgsql" ] && [ -n "${DB_HOST:-}" ]; then
    echo "Waiting for PostgreSQL at ${DB_HOST}:${DB_PORT:-5432}..."
    for attempt in $(seq 1 30); do
        if php -r '
            $host = getenv("DB_HOST");
            $port = getenv("DB_PORT") ?: "5432";
            $db = getenv("DB_DATABASE");
            $user = getenv("DB_USERNAME");
            $pass = getenv("DB_PASSWORD");
            try {
                new PDO("pgsql:host={$host};port={$port};dbname={$db}", $user, $pass, [PDO::ATTR_TIMEOUT => 2]);
                exit(0);
            } catch (Throwable $e) {
                exit(1);
            }
        '; then
            break
        fi

        if [ "${attempt}" = "30" ]; then
            echo "PostgreSQL was not reachable after 30 attempts." >&2
            exit 1
        fi

        sleep 2
    done
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${APP_ENV:-local}" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
