# 🌐 Guía de Despliegue en 2 Servidores Ubuntu con Docker (Apache)

Esta guía detalla el despliegue del proyecto **IkasKude (Laravel 12)** en dos servidores Ubuntu desacoplados:
1. **Servidor 1 (Ubuntu - App Web):** Contenedor con **Apache + PHP 8.3**. Usa el `.htaccess` nativo de Laravel y no necesita configuraciones intermedias de FastCGI ni Supervisor.
2. **Servidor 2 (Ubuntu - Base de Datos):** Contenedor con **MySQL 8.0** y volumen persistente.

---

## 🗄️ PASO 1: Servidor de Base de Datos (Ubuntu #2)

### 1.1 Copiar los archivos
Copia la carpeta `docker/db-server` al servidor de base de datos (ejemplo: `/home/usuario/mysql`):
```bash
scp -r docker/db-server usuario@IP_DEL_SERVIDOR_BD:/home/usuario/mysql
```

### 1.2 Configurar credenciales y arrancar
En el Servidor #2:
```bash
cd /home/usuario/mysql
cp .env.example .env
nano .env   # Establece contraseñas seguras para root y ciber_user
docker compose up -d
```

### 1.3 Cortafuegos (UFW)
Permite el acceso al puerto 3306 **únicamente** desde la IP del Servidor 1 (App):
```bash
sudo ufw allow from IP_DEL_SERVIDOR_APP to any port 3306 proto tcp
sudo ufw enable
```

---

## 🚀 PASO 2: Servidor de la Aplicación Laravel (Ubuntu #1)

### 2.1 Clonar el proyecto
```bash
git clone <URL_DEL_REPOSITORIO> ikaskude
cd ikaskude
```

### 2.2 Configurar el `.env`
```bash
cp .env.production.example .env
nano .env
```
Ajusta la conexión de la base de datos con la IP del Servidor #2:
```ini
APP_NAME=IkasKude
APP_ENV=production
APP_DEBUG=false
APP_URL=http://tu-dominio-o-ip.com

DB_CONNECTION=mysql
DB_HOST=IP_DEL_SERVIDOR_BD
DB_PORT=3306
DB_DATABASE=ciber_plataforma
DB_USERNAME=ciber_user
DB_PASSWORD=Contraseña_Configurada_En_El_Servidor_BD
```

### 2.3 Construir y arrancar
```bash
docker compose up -d --build
```

### 2.4 Ejecutar migraciones y crear usuario administrador
```bash
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
```

---

## 🛠️ Comandos de Mantenimiento

* **Ver logs en tiempo real:**
  ```bash
  docker compose logs -f
  ```
* **Acceder al contenedor:**
  ```bash
  docker compose exec app bash
  ```
* **Actualizar con nuevos cambios:**
  ```bash
  git pull origin main
  docker compose up -d --build
  docker compose exec app php artisan migrate --force
  docker compose exec app php artisan optimize:clear
  ```
