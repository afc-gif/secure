FROM php:8.3-apache AS base

RUN apt-get update \
    && apt-get install -y --no-install-recommends git curl unzip libpq-dev libzip-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip bcmath opcache \
    && a2dismod mpm_event \
    && a2enmod mpm_prefork rewrite headers \
    && apache2ctl configtest \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader

COPY package.json ./
RUN npm install --ignore-scripts

COPY . .
RUN npm run build \
    && composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/cca-entrypoint
RUN chmod +x /usr/local/bin/cca-entrypoint

EXPOSE 8080

ENTRYPOINT ["cca-entrypoint"]
CMD ["apache2-foreground"]
