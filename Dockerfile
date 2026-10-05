# ==============================================================================
# 1. Compilación de Assets con Node.js & Vite
# ==============================================================================
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci || npm install

COPY resources/ ./resources/
COPY vite.config.js ./
RUN mkdir -p public/build
RUN npm run build || true

# ==============================================================================
# 2. Instalación de dependencias de Composer (PHP)
# ==============================================================================
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# ==============================================================================
# 3. Contenedor Final con Apache y PHP 8.3
# ==============================================================================
FROM php:8.3-apache AS runner

# Instalar extensiones requeridas por Laravel y MySQL
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    bcmath \
    intl \
    zip \
    gd \
    opcache

# Habilitar mod_rewrite (esencial para el .htaccess de Laravel) y mod_headers
RUN a2enmod rewrite headers

WORKDIR /var/www/html

# Configuraciones de Apache y PHP
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Script de entrada (permisos, conexión a BD, caches)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Copiar el código fuente y las dependencias compiladas
COPY . /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY --from=frontend /app/public/build /var/www/html/public/build

# Autodescubrimiento de paquetes Laravel
RUN php artisan package:discover --ansi || true

# Permisos para el usuario de Apache (www-data)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Healthcheck nativo de Laravel 12
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
