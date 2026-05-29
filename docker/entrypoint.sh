#!/bin/sh
set -e

PORT="${PORT:-8080}"

sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/sites-available/default

if [ ! -f .env ]; then
    cp .env.example .env
    # Ensure the copied .env reflects the runtime APP_ENV so Laravel
    # doesn't fall back to "local" and try to reach a Vite dev server.
    if [ -n "${APP_ENV:-}" ]; then
        sed -i "s/^APP_ENV=.*/APP_ENV=${APP_ENV}/" .env
    fi
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
    # Verify the Vite manifest was built into the image; without it the
    # @vite() helper cannot inject asset links into rendered HTML.
    if [ ! -f public/build/manifest.json ]; then
        echo "WARNING: public/build/manifest.json not found. Run 'npm run build' during the Docker build step." >&2
    fi

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Start PHP-FPM and Nginx
php-fpm -D
exec nginx -g "daemon off;"
