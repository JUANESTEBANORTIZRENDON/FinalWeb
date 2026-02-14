# Mejoras recomendadas (tecnologías y herramientas) — ArteConecta

Este documento propone mejoras **detalladas** para ArteConecta después de revisar el código y su arquitectura actual. Incluye **qué mejorar**, **por qué** y **qué tecnologías/herramientas** usar para implementarlo.

> Nota: El análisis funcional/arquitectónico general del proyecto está en `documentacion/ANALISIS_PROYECTO.md`.

---

## 1) Prioridades (qué atacar primero)

### P0 — Seguridad y estabilidad (alto impacto)
1) **Eliminar secretos del repositorio**
   - Problema: hay credenciales hardcodeadas en `config/database.php` y además `.env` existe en el repo local.
   - Riesgo: filtración de base de datos (Neon) y compromiso total.
   - Mejora:
     - Mover todo a variables de entorno (solo `.env`) y nunca commitear `.env`.
     - Usar *secrets management* en despliegues.
   - Herramientas:
     - Git: `.gitignore` (ya existe, pero validar que ignore `.env`).
     - En CI/CD: **GitHub Actions Secrets** / **Vercel/Render/Fly secrets** / **Docker secrets**.
     - Opcional: **1Password Secrets Automation**, **Doppler**, **Infisical**.

2) **Corregir el “modo pooler” de Neon y booleans**
   - Situación actual: se activó `PDO::ATTR_EMULATE_PREPARES` para host tipo `*-pooler*` (PgBouncer), y se ajustaron comparaciones de booleanos a `'true'/'false'`.
   - Mejora:
     - Preferir host **directo** (no pooler) para **migraciones** y operaciones administrativas.
     - Separar variables: `DB_HOST_POOLER` para la app y `DB_HOST_DIRECT` para CLI/migrate.
   - Herramientas:
     - `.env` por entorno y perfiles (local/staging/prod).
     - Scripts npm/composer para ejecutar migraciones con host directo.

3) **APP_DEBUG y hardening de producción**
   - Mejora:
     - `APP_DEBUG=false` en producción.
     - Configurar `APP_ENV=production`, `LOG_LEVEL`, `SESSION_SECURE_COOKIE`, `TRUSTED_PROXIES`.
   - Herramientas:
     - Laravel config caching: `php artisan config:cache`.
     - Revisión de cabeceras: middleware + reverse proxy.

### P1 — Mantenibilidad (medio/alto impacto)
4) **Mover “datos por defecto” de migraciones a seeders**
   - Situación: `art_categories` inserta categorías por defecto dentro de la migración.
   - Problema: mezcla “estructura” con “datos”, dificulta rollback y testing.
   - Mejora:
     - Dejar la migración solo con `Schema::create`.
     - Crear un seeder `ArtCategoriesSeeder` y llamarlo desde `DatabaseSeeder`.
   - Herramientas:
     - Seeders de Laravel, factories (ya existe `UserFactory.php`).

5) **Políticas y autorización (reemplazar lógica dispersa)**
   - Situación: hay validaciones de propiedad en closures/middleware dentro de controladores.
   - Mejora:
     - Crear **Policies** (`php artisan make:policy ArtworkPolicy`) y usar `authorize()` / `@can`.
     - Consolidar permisos: “solo el artista dueño puede editar/eliminar”.
   - Herramientas:
     - Laravel Policies + Gates.

### P2 — Calidad, performance y experiencia de desarrollo
6) **Tests automatizados**
   - Qué testear (mínimo):
     - Registro de usuario con `user_type`.
     - Artista crea obra (subida de imagen fake), edición, borrado.
     - Like / Unlike, comentar, follow/unfollow.
     - Acceso: visitante no puede crear obra, artista no puede like a su propia obra, etc.
   - Herramientas:
     - PHPUnit (ya está) o **Pest** (recomendado por DX).
     - `Illuminate\Http\UploadedFile::fake()` para imágenes.
     - Opcional E2E: **Laravel Dusk**.

7) **Estandarizar formato y análisis estático**
   - Herramientas:
     - **Laravel Pint** (ya está como dev dependency) para formateo.
     - **Larastan (PHPStan)** para análisis estático.
     - Opcional: **Rector** para refactors automáticos y upgrades.

8) **Observabilidad**
   - Mejora:
     - Centralizar errores y performance.
     - Reducir logs de depuración en producción (por ejemplo, en `ArtworkController`).
   - Herramientas:
     - **Sentry** (Laravel SDK) o **Bugsnag**.
     - **Laravel Telescope** (solo local/staging) para requests, queries, jobs.

---

## 2) Backend (Laravel) — mejoras concretas

### 2.1 Separación de responsabilidades (Controller → Service/Action)
**Problema:** algunos controladores hacen mucho (validación + archivos + DB + reglas de negocio + logs).

**Mejora:**
- Crear clases tipo:
  - `App\Actions\Artworks\CreateArtwork`
  - `App\Actions\Artworks\UpdateArtwork`
  - `App\Actions\Artworks\DeleteArtwork`
- Dejar el controller como “orquestador” (request → action → response).

**Herramientas/patrones:**
- Actions/Services (patrón simple, sin librerías).
- DTOs: `spatie/laravel-data` (opcional) para tipar y validar datos de entrada.

### 2.2 Requests dedicados (FormRequest)
**Mejora:**
- Crear `StoreArtworkRequest` y `UpdateArtworkRequest` para reglas y mensajes.
- Ventaja: testear validación más fácil y controller más limpio.

**Herramientas:**
- `php artisan make:request StoreArtworkRequest`

### 2.3 Authorization via Policies

**Mejora:**
- `ArtworkPolicy@update/delete/view` y aplicar:
  - En controller: `$this->authorize('update', $artwork);`
  - En Blade: `@can('update', $artwork) ... @endcan`
- Centraliza permisos y evita duplicación.

### 2.4 Base de datos: integridad, índices y consistencia
**Mejoras sugeridas:**
- Añadir índices por consultas frecuentes:
  - `artworks(is_public, created_at)`
  - `artworks(artist_id, created_at)`
  - `comments(artwork_id, created_at)`
  - `artwork_likes(artwork_id)`
  - `followers(artist_id)` y `followers(follower_id)`
- Validar longitudes y `nullable` según UI.

**Herramientas:**
- Migraciones incrementales (`php artisan make:migration add_indexes_to_artworks`).
- `EXPLAIN ANALYZE` en Neon (consola/SQL editor) para queries pesadas.

### 2.5 Seeders + factories
**Mejora:**
- Crear seeders para:
  - categorías de arte,
  - usuarios demo (artista/visitante),
  - obras demo con imágenes fake (solo local).

**Herramientas:**
- Factories (ya existe `database/factories/UserFactory.php`).
- `Storage::fake('public')` en tests.

### 2.6 Storage de imágenes (robustez)
**Problemas comunes:**
- `storage:link` faltante → imágenes no cargan.
- Diferencias entre `Storage::url($path)` y `asset('storage/'.$path)`.

**Mejora:**
- Estandarizar a `Storage::disk('public')->url($path)` en todas las vistas.
- Validar/eliminar archivos huérfanos al borrar obras.

**Herramientas:**
- `php artisan storage:link`
- Jobs para limpieza (`php artisan make:job CleanupOrphanFiles`) (opcional).

### 2.7 Jobs/Queue para tareas pesadas (PDF, notificaciones)
**Mejora:**
- Generación de PDF y/o procesamiento de imágenes en background.

**Herramientas:**
- Queue driver: Redis recomendado.
- **Laravel Horizon** (monitor de colas) si usas Redis.

### 2.8 Cache
**Mejora:**
- Cachear:
  - listado de categorías,
  - conteos agregados (likes/comentarios) si crece el tráfico.

**Herramientas:**
- Cache store: Redis.
- `Cache::remember(...)`.

---

## 3) Frontend (Vite + Tailwind + Alpine) — mejoras

### 3.1 Unificación UI (Bootstrap vs Tailwind)
Se ven clases de Bootstrap y también Tailwind. Mezclar ambos suele:
- duplicar estilos,
- aumentar CSS final,
- crear inconsistencias visuales.

**Mejora:**
- Elegir uno como base.
  - Si el proyecto ya tiene mucho Bootstrap en vistas, mantener Bootstrap y usar Tailwind solo si es necesario.
  - O migrar progresivamente a Tailwind.

**Herramientas:**
- Vite build (`npm run build`) + auditoría de bundle.

### 3.2 Componentización Blade
**Mejora:**
- Crear componentes Blade para tarjetas de obra, botones like/follow, etc.

**Herramientas:**
- `php artisan make:component ArtworkCard`

### 3.3 Interacciones (AJAX) más robustas
**Mejora:**
- Manejar estados:
  - loading,
  - errores 401/403,
  - reintentos,
  - mensajes UI consistentes.

**Herramientas:**
- `axios` ya está instalado.
- Alpine store/global state (opcional).

---

## 4) Calidad de código (lint, formato, estático)

### 4.1 Formato
**Herramienta:** Laravel Pint (ya incluido).
- Ejecutar: `php artisan pint`
- Integrar en CI.

### 4.2 Análisis estático
**Herramienta recomendada:** Larastan (PHPStan para Laravel).
- Beneficios:
  - detectar tipos incorrectos,
  - relaciones mal usadas,
  - problemas de nullability.

### 4.3 Refactors automáticos
**Herramienta:** Rector (opcional).
- Para limpiar controllers grandes y modernizar sintaxis.

---

## 5) Testing (unit/feature/e2e)

### 5.1 Feature tests (recomendado como base)
Casos clave:
- Un visitante no accede a rutas `artist`.
- Un artista crea obra con imagen válida.
- Like/unlike y follow/unfollow devuelven JSON correcto.
- Una obra privada no se ve públicamente.

**Herramientas:**
- PHPUnit (ya está) o Pest.

### 5.2 E2E
**Herramienta:** Laravel Dusk (opcional).
- Prueba flujo real: login → crear obra → ver en galería.

---

## 6) CI/CD (automatización)

### 6.1 Pipeline recomendado (GitHub Actions)
Jobs mínimos:
- `composer install`
- `php artisan test`
- `php artisan pint --test` (para no formatear, solo validar)
- `npm ci` + `npm run build`

**Herramientas:**
- GitHub Actions
- Cache de dependencias (composer/npm)

### 6.2 Entornos (staging/prod)
**Mejora:**
- Base de datos y variables separadas por entorno.
- Migraciones con host directo en Neon.

**Herramientas:**
- `.env.staging`, `.env.production` (no en repo), secrets del proveedor.

---

## 7) Observabilidad y monitoreo

**Mejoras:**
- Alertas de errores y performance.
- Correlación request-id.

**Herramientas:**
- Sentry/Bugsnag
- Telescope (local)
- Logs estructurados (Monolog channels)

---

## 8) Internacionalización (i18n) y encoding

**Problema observado:** textos “mojibake” (`Ã¡`, `Â¿`) en algunos archivos.

**Mejora:**
- Asegurar que archivos Blade/PHP estén en UTF-8.
- Configurar idioma:
  - `APP_LOCALE=es`
  - `config/app.php` → `locale` y `fallback_locale`.

**Herramientas:**
- Archivos `lang/es/*.php`.

---

## 9) Seguridad (checklist rápido)

Recomendaciones:
- CSRF ya está por defecto (web).
- Validar uploads (ya hay reglas, reforzar):
  - limitar MIME + tamaño,
  - considerar escaneo antivirus (si producción).
- Rate limiting para endpoints de interacción si crece el tráfico.
- Headers de seguridad (CSP, HSTS) a nivel proxy/servidor.

Herramientas:
- Laravel RateLimiter + middleware `throttle`.
- Nginx/Cloudflare para WAF/CDN.

---

## 10) Siguientes pasos sugeridos (plan en 5 tareas)

1) Quitar credenciales hardcodeadas y asegurar `.env` fuera de repo.
2) Crear seeders para categorías y datos demo.
3) Implementar policies + form requests para artworks.
4) Agregar tests feature (crear obra / likes / follow).
5) Configurar CI (pint + tests + build).
