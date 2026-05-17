# Kusay.pe - Portal inmobiliario (Laravel 12)

Kusay.pe es una plataforma web para publicar, buscar y gestionar propiedades inmobiliarias, enfocada en operaciones de venta, alquiler y proyectos inmobiliarios.

El proyecto incluye:
- Portal publico con filtros, destacados y detalle de propiedad.
- Autenticacion con email/password y login social (Google/Facebook).
- Gestion de publicaciones por usuario.
- Recepcion de solicitudes de contacto.
- Favoritos, comentarios y resenas.
- Panel administrativo para tipos de propiedad, usuarios, soporte y sugerencias.
- Flujo de verificacion de perfil de usuario.

---

## 1. Stack tecnico

- PHP `^8.2`
- Laravel `^12`
- Vite `^7`
- Tailwind CSS + CSS modular por layout/pagina
- Alpine.js
- Base de datos: SQLite/MySQL (configurable por `.env`)

Dependencias principales:
- `laravel/framework`
- `laravel/socialite`
- `laravel/breeze`

---

## 2. Requisitos previos

Antes de iniciar, valida tener instalado:

1. PHP 8.2+
2. Composer 2+
3. Node.js 20.19+ (recomendado LTS actual)
4. npm 10+
5. Motor de base de datos (SQLite o MySQL)

Nota importante:
- Este proyecto usa Vite 7. Si usas una version menor de Node (por ejemplo 20.13), `npm run build` puede fallar.

---

## 3. Instalacion local

### Opcion rapida (script de Composer)

```bash
composer run setup
```

El script hace:
1. `composer install`
2. Crea `.env` desde `.env.example` si no existe
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### Opcion paso a paso

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

Si usaras almacenamiento publico de imagenes:

```bash
php artisan storage:link
```

---

## 4. Configuracion de entorno (`.env`)

Variables clave:

### Aplicacion
- `APP_NAME`
- `APP_ENV`
- `APP_DEBUG`
- `APP_URL`

### Base de datos
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Correo
- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_FROM_ADDRESS`

### Login social
- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`
- `FACEBOOK_CLIENT_ID`
- `FACEBOOK_CLIENT_SECRET`
- `FACEBOOK_REDIRECT_URI`

---

## 5. Comandos utiles

### Desarrollo completo (app + cola + logs + vite)

```bash
composer run dev
```

### Servidor y frontend por separado

```bash
php artisan serve
npm run dev
```

### Testing

```bash
composer run test
```

o

```bash
php artisan test
```

### Build de produccion

```bash
npm run build
```

### Cache para produccion

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 6. Seeders y datos base

`DatabaseSeeder` ejecuta por defecto:
- `TipoPropiedadSeeder`
- `SupportTermsSeeder`

Seeder opcional de demo:
- `PropiedadDemoSeeder` (comentado por defecto)

Si quieres poblar con datos demo:

1. Abre `database/seeders/DatabaseSeeder.php`
2. Descomenta `PropiedadDemoSeeder::class`
3. Ejecuta:

```bash
php artisan db:seed
```

---

## 7. Rutas principales

### Portal publico
- `GET /` -> home con filtros y listados
- `GET /portal/propiedades/{id}` -> detalle de propiedad
- `GET /como-publicar` -> guia para publicar
- `GET /soporte/*` -> secciones de soporte

### Acciones sobre propiedades (portal)
- `POST /portal/propiedades/{id}/clic`
- `POST /portal/propiedades/{id}/favorito/toggle`
- `POST /portal/propiedades/{id}/contacto`
- `POST /portal/propiedades/{id}/comentarios`
- `POST /portal/propiedades/{id}/resenas`

### Usuario autenticado
- `GET /mis-publicaciones`
- `GET /mis-solicitudes`
- CRUD de publicaciones
- `GET/PATCH /profile`
- `GET/POST /profile/verificacion`

### Admin
Prefijo `admin/*` con middleware `auth + verified + admin` para:
- dashboard
- tipos de propiedad
- soporte
- sugerencias
- usuarios
- verificacion de usuarios

---

## 8. Estructura resumida

```text
app/
  Http/Controllers/      # Logica de portal, propiedades, admin, auth
  Models/                # Entidades Eloquent
resources/
  views/                 # Blade templates
  css/                   # Estilos por layout/pagina
  js/                    # Entrada JS (Vite)
routes/
  web.php                # Rutas principales
  auth.php               # Rutas de autenticacion
database/
  migrations/            # Esquema
  seeders/               # Datos iniciales y demo
tests/
  Feature/               # Pruebas funcionales
  Unit/                  # Pruebas unitarias
```

---

## 9. Rendimiento aplicado (resumen)

El proyecto ya incluye varias mejoras de rendimiento:
- Cache en catalogos y listados auxiliares.
- Limitacion de escritura de visitas/clics por ventana de tiempo.
- Cache HTTP para imagenes (`ETag`, `Last-Modified`, `Cache-Control`).
- `lazy loading` en imagenes de tarjetas.
- Separacion de bundles CSS por layout (`client-app.css` y `admin-app.css`).

---

## 10. Troubleshooting rapido

### Error al construir frontend con Vite
Problema:
- Version de Node incompatible.

Solucion:
1. Actualiza Node a 20.19+ o 22.12+
2. Elimina `node_modules` y reinstala:

```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

### No cargan imagenes subidas
Solucion:

```bash
php artisan storage:link
```

### Cambios de estilos no se ven
Solucion:
1. Verifica que Vite este levantado (`npm run dev`)
2. Limpia caches:

```bash
php artisan optimize:clear
```

---

## 11. Pruebas disponibles

En `tests/Feature` y `tests/Unit` hay pruebas para:
- portal y filtros
- gestion de propiedades
- perfil
- dashboard admin
- validaciones de catalogo ubigeo

Ejecutar:

```bash
php artisan test
```

---

## 12. Contribucion (flujo sugerido)

1. Crea una rama nueva
2. Implementa cambios pequenos y atomicos
3. Corre pruebas
4. Adjunta capturas si hay cambios de UI
5. Abre PR con descripcion clara

---

## 13. Contacto

Si necesitas soporte funcional o tecnico del proyecto:
- Email: `kusaycontacto@gmail.com`
- Proyecto: Kusay.pe
