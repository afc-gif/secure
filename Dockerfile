FROM php:8.3-fpm-alpine AS php

RUN apk add --no-cache git curl unzip postgresql-dev libzip-dev icu-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip bcmath opcache

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache nodejs npm

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader

COPY package.json ./
RUN npm install --ignore-scripts

COPY . .
RUN npm run build \
    && composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

FROM nginx:alpine

RUN apk add --no-cache curl

# Copy PHP installation from build stage
COPY --from=php /usr/local /usr/local

# Copy application files
COPY --from=php /var/www/html /var/www/html

# Copy Nginx and entrypoint config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/cca-entrypoint

RUN chmod +x /usr/local/bin/cca-entrypoint

EXPOSE 8080

ENTRYPOINT ["cca-entrypoint"]
CMD ["nginx", "-g", "daemon off;"]
