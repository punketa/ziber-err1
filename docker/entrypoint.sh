#!/bin/bash
set -e

echo "=== Arrancando Contenedor Laravel Apache (IkasKude) ==="

# 1. Asegurar directorios de trabajo
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# 2. Ajustar permisos para www-data (usuario de Apache)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Comprobar conexion con el servidor MySQL remoto
if [ -n "$DB_HOST" ] && [ "$DB_CONNECTION" = "mysql" ]; then
    echo ">> Comprobando conexion con la base de datos en $DB_HOST:${DB_PORT:-3306}..."
    MAX_TRIES=30
    COUNTER=0
    until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT') ?: 3306), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
        COUNTER=$((COUNTER + 1))
        if [ $COUNTER -ge $MAX_TRIES ]; then
            echo ">> [AVISO] Espera agotada al conectar a MySQL en $DB_HOST. Continuando arranque..."
            break
        fi
        echo ">> Esperando disponibilidad de MySQL ($COUNTER/$MAX_TRIES)..."
        sleep 2
    done
    if [ $COUNTER -lt $MAX_TRIES ]; then
        echo ">> Conexion con la base de datos confirmada."
    fi
fi

# 4. Comprobar APP_KEY
if [ -z "$APP_KEY" ] && [ -f /var/www/html/.env ]; then
    if grep -q "^APP_KEY=$" /var/www/html/.env || ! grep -q "^APP_KEY=" /var/www/html/.env; then
        echo ">> Generando APP_KEY..."
        php artisan key:generate --force
    fi
fi

# 5. Enlace simbolico storage:link
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# 6. Optimizacion de caches en produccion
if [ "$APP_ENV" = "production" ]; then
    echo ">> Modo produccion: cacheando configuracion, rutas y vistas..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo ">> Modo desarrollo ($APP_ENV): limpiando caches..."
    php artisan optimize:clear || true
fi

# 7. Migraciones automaticas si esta habilitado
if [ "$AUTORUN_MIGRATIONS" = "true" ] || [ "$AUTORUN_MIGRATIONS" = "1" ]; then
    echo ">> Ejecutando migraciones..."
    php artisan migrate --force || echo ">> [AVISO] Fallo al ejecutar migraciones."
fi

echo "=== Apache listo para recibir peticiones ==="

# Ejecuta apache2-foreground
exec "$@"
