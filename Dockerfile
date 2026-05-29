FROM php:8.3-fpm AS php

RUN apt-get update \
    && apt-get install -y --no-install-recommends git curl unzip libpq-dev libzip-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

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

COPY --from=php /var/www/html /var/www/html
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/cca-entrypoint

RUN chmod +x /usr/local/bin/cca-entrypoint

EXPOSE 8080

ENTRYPOINT ["cca-entrypoint"]
CMD ["nginx", "-g", "daemon off;"]
