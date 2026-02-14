# Análisis del proyecto: ArteConecta

Este documento describe qué hace el proyecto, cómo está organizado, qué componentes principales tiene y qué aspectos técnicos conviene tener en cuenta para mantenerlo y desplegarlo.

## 1) Resumen ejecutivo

**ArteConecta** es una plataforma web construida con **Laravel 10** (PHP 8.1+) orientada a conectar **artistas** y **visitantes** alrededor de una **galería de obras**:

- Los **artistas** publican y gestionan sus obras (subida de imagen + metadatos).
- Los **visitantes** descubren obras, dejan **comentarios**, dan **likes** y **siguen** artistas.
- Incluye un **dashboard** con contenido personalizado para visitantes.
- Incluye **recomendaciones de libros** (integración con Google Books API).
- Los artistas pueden descargar un **reporte PDF** de sus publicaciones e interacciones.

La base de datos está configurada para **PostgreSQL en Neon Tech**, usando UUIDs como claves primarias.

## 2) Stack tecnológico

### Backend
- **Laravel 10** (`composer.json`)
- **Sanctum** (tokens / API “first-party”, scaffold incluido) (`laravel/sanctum`)
- **Breeze** (auth scaffolding con Blade) (`laravel/breeze`)
- **DomPDF** para generar PDFs (`barryvdh/laravel-dompdf`)

### Frontend
- **Vite** como bundler (`vite.config.js`, `package.json`)
- **Tailwind CSS** + `@tailwindcss/forms`
- **Alpine.js**
- Vistas con **Blade** (`resources/views`)

### Infraestructura / Base de datos
- **PostgreSQL** en **Neon**.
- IDs UUID (`uuid-ossp` / `uuid_generate_v4()` en migraciones).
- Se añadió un proveedor para construir el DSN con `options=endpoint=...` específico de Neon (`app/Providers/NeonDatabaseServiceProvider.php`).

## 3) Estructura del repositorio (alto nivel)

- `app/Http/Controllers/`: controladores (galería, perfiles, dashboard, follow/like/comentarios, reportes).
- `app/Models/`: modelos Eloquent (User, Artwork, Follower, Comment, Like, Notification, Category).
- `routes/web.php`: rutas principales de la aplicación.
- `routes/auth.php`: rutas de autenticación (Breeze adaptado).
- `resources/views/`: UI Blade (login/registro, dashboard visitante, perfil artista, galería, detalle, etc.).
- `database/migrations/`: estructura de BD.
- `public/` + `storage/`: assets y archivos subidos (con `storage:link`).

## 4) Dominio funcional (qué hace la app)

### 4.1 Roles y acceso
El modelo `User` tiene el campo `user_type` con valores:
- `artist`
- `visitor`

Helpers:
- `User::isArtist()`
- `User::isVisitor()`

Middleware:
- `artist` (`app/Http/Middleware/ArtistMiddleware.php`) restringe rutas a artistas.

Flujo de registro:
- En el registro el usuario elige tipo (`artist` o `visitor`) y se guarda en `users.user_type`.

### 4.2 Obras de arte (Artworks)
Funciones principales:
- **Galería pública**: lista obras públicas paginadas.
- **Detalle de obra**: muestra obra, comentarios, likes y perfil del artista; permite seguir al artista (si el visitante está autenticado y no es el mismo artista).
- **Gestión del artista**:
  - crear obra (subir imagen, metadatos y estado público/privado),
  - editar,
  - eliminar (incluye limpieza de imagen y dependencias como likes/comentarios).

Campos relevantes (migración `artworks`):
- `artist_id` (UUID)
- `title`, `description`
- `category_id`
- `image_path` (ruta en disco público)
- `creation_date`, `technique`, `dimensions`
- `is_public` (boolean)
- timestamps

### 4.3 Interacciones sociales
- **Likes**: `artwork_likes` con unique `(artwork_id, user_id)`.
- **Comentarios**: `comments` con `content` y timestamps.
- **Seguidores**: `followers` con unique `(artist_id, follower_id)`.

### 4.4 Dashboard del visitante

El dashboard (`/dashboard`) para visitantes muestra:
- Obras recientes públicas.
- Obras públicas de artistas seguidos.
- Obras públicas a las que el usuario les dio “me gusta”.

Controlador: `app/Http/Controllers/DashboardController.php`.

## 5) Rutas principales (web)

Archivo: `routes/web.php`.

### Públicas
- `/` → `welcome`
- `/artworks` → galería pública
- `/artworks/{artwork}` → detalle de obra (con control de privacidad)
- `/artists` → listado de artistas
- `/artists/view/{id}` → perfil público de artista (UUID)

### Autenticadas
- `/dashboard` → dashboard (redirecciona a perfil si es artista)
- `/profile` → edición de perfil “genérico” (scaffold Breeze)
- `/biblioteca-arte` → recomendaciones de libros (Google Books)
- `/visitor/profile/edit` + update + convertir a artista

### Solo artista (middleware `artist`)
- `/my-profile` + edit + update → perfil de artista
- `/my-publications-report` → descarga PDF
- `/my-artworks` + create/store/edit/update/destroy → gestión de obras

### Interacciones (requieren login)
- `POST /artworks/{artwork}/like`
- `POST /artworks/{artwork}/comments`
- `POST /artists/{artistId}/follow`

## 6) Modelos y relaciones (Eloquent)

### `User`
- `hasMany(Artwork)` por `artist_id`
- `hasMany(ArtworkLike)`
- `hasMany(Comment)`
- `hasMany(Follower)` como `followers()` (artista → seguidores)
- `hasMany(Follower)` como `following()` (usuario → artistas seguidos)
- `belongsToMany(User)` vía tabla `followers` como `followedArtists()`
- `hasMany(Notification)`

### `Artwork`
- `belongsTo(User)` como `artist`
- `belongsTo(ArtCategory)` como `category`
- `hasMany(ArtworkLike)` como `likes`
- `hasMany(Comment)` como `comments`

### `Follower`
Relaciona un usuario seguidor con un artista.

### `Comment`
Comentarios sobre una obra (relación con `user` y `artwork`).

### `ArtworkLike`
Like sobre una obra (relación con `user` y `artwork`).

### `Notification`
Modelo propio para notificaciones (tabla `notifications`).

## 7) Base de datos (PostgreSQL / Neon)

### 7.1 UUIDs y extensión `uuid-ossp`
Las migraciones usan `public.uuid_generate_v4()` como default de UUID.
Para ello se habilita la extensión:
- `CREATE EXTENSION IF NOT EXISTS "uuid-ossp"`

**Nota importante:** en Neon/PG puede dar problemas ejecutar `CREATE EXTENSION` dentro de una migración transaccional. Por eso la migración de `users` se ajustó para ejecutarse fuera de transacción.

### 7.2 Neon pooler + prepared statements (lección aprendida)
Neon ofrece hosts tipo `*-pooler.*` (PgBouncer). En ese modo, los prepared statements del servidor pueden fallar.

Se aplicó una mitigación en `app/Providers/NeonDatabaseServiceProvider.php`:
- Si el host parece “pooler”, se activa `PDO::ATTR_EMULATE_PREPARES=true`.

Efectos secundarios observados:
- Comparaciones de booleanos pueden terminar como `is_public = 1`.
- Postgres no acepta `boolean = integer`, así que se ajustaron consultas para comparar con `'true'` / `'false'` cuando aplica.

## 8) Subida y almacenamiento de imágenes

Las imágenes se guardan en el disco `public` (Laravel filesystem):
- Carpeta esperada: `storage/app/public/artworks`
- Ruta guardada en DB: `artworks/<archivo>.jpg`

Requisito para que se vean en el navegador:
- Ejecutar `php artisan storage:link` (crea `public/storage` → `storage/app/public`).

Vistas:
- Algunas usan `Storage::url(...)` y otras `asset('storage/' . $path)`. Ambas funcionan si el symlink existe.

## 9) Integraciones externas

### 9.1 Google Books API
Controlador: `BookRecommendationController`.
- Llama a `https://www.googleapis.com/books/v1/volumes` sin API key.
- Filtra por `subject:art` y `langRestrict=es`.

### 9.2 Reportes PDF (DomPDF)
Controlador: `ReportController`.
- Renderiza la vista `resources/views/reports/publications.blade.php`.
- Descarga `reporte-publicaciones-YYYY-MM-DD.pdf`.

## 10) Estado actual de “seeders”

`database/seeders/DatabaseSeeder.php` está vacío.
Las categorías por defecto se insertan directamente en la migración `2023_05_29_120001_create_art_categories_table.php`.

Recomendación: mover la inserción de datos por defecto a un **Seeder** para separar “estructura” de “datos”.

## 11) Observaciones de calidad y riesgos

### 11.1 Credenciales sensibles en el repositorio
Se observó password en texto plano dentro de `config/database.php` y scripts sueltos con credenciales.

Recomendación:
- Nunca hardcodear credenciales en `config/*.php`.
- Usar únicamente variables de entorno (`.env`) y excluir `.env` del control de versiones.

### 11.2 `APP_DEBUG=true`
En producción debe ser `false`.

### 11.3 Idioma/encoding
Hay textos en español, pero `config/app.php` tiene `locale` en `en` y se observan caracteres “mojibake” en algunos archivos (`Ã¡`, `Â¿`).

Recomendación:
- Asegurar que los archivos estén en UTF-8.
- Ajustar `APP_LOCALE=es` y `config/app.php` si se desea.

### 11.4 Logging de depuración
`ArtworkController` contiene mucho logging orientado a depuración. Útil en desarrollo, pero conviene reducirlo o controlarlo por entorno en producción.

## 12) Guía rápida para correr el proyecto (local)

1) Dependencias backend:
- `composer install`
2) Variables de entorno:
- `copy .env.example .env` (si aplica)
- `php artisan key:generate`
3) Dependencias frontend:
- `npm install`
4) Assets:
- `npm run dev` (desarrollo) o `npm run build` (build)
5) Storage:
- `php artisan storage:link`
6) Base de datos:
- `php artisan migrate`
7) Servidor:
- `php artisan serve`

## 13) Ideas de mejora (roadmap)

- Extraer “datos por defecto” a seeders.
- Unificar el helper de “publicado” (`is_public`) y revisar filtros en todos los queries.
- Añadir tests (feature tests para: crear obra, like, comment, follow).
- Normalizar UI (consistencia entre Bootstrap/Tailwind si se mezclan).
- Añadir políticas (Laravel Policies) para permisos por obra en vez de closures en middleware.
