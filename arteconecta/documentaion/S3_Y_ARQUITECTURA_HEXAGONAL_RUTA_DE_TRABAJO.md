# ArteConecta — Guardado de fotos en Amazon S3 + Ruta de migración a Arquitectura Hexagonal

Este documento explica (1) cómo reorganizar el guardado/lectura de imágenes para usar **Amazon S3** en lugar de `storage/public`, y (2) una **ruta de trabajo** para migrar el proyecto a **arquitectura hexagonal** sin “romper” la aplicación en el proceso.

## 0) Estado actual (resumen)

En el proyecto, hoy las imágenes se guardan en el disco `public` de Laravel:
- Obras: `app/Http/Controllers/ArtworkController.php` usa `storeAs(..., 'public')` y persiste `image_path` en DB (ej: `artworks/archivo.jpg`).
- Avatares: `app/Http/Controllers/ArtistProfileController.php` y `app/Http/Controllers/VisitorProfileController.php` guardan `avatar_path` en `public`.
- La UI muestra imágenes usando `Storage::url(...)` o `asset('storage/'.$path)` (Blade).

Objetivo: que **obras** y **avatares** se guarden en **S3**, y que la app genere URLs correctas (públicas o firmadas).

---

## 1) Amazon S3 en Laravel (implementación recomendada)

### 1.1 Dependencia requerida

Laravel 10 usa Flysystem v3. Para S3 se requiere el adaptador:
- Paquete: `league/flysystem-aws-s3-v3`

Comando:
- `composer require league/flysystem-aws-s3-v3 "^3.0"`

### 1.2 Variables de entorno

Editar `.env`:

- `FILESYSTEM_DISK=s3` (si quieres que todo use S3 por defecto) **o** mantener `local/public` y usar S3 solo para imágenes.
- `AWS_ACCESS_KEY_ID=...`
- `AWS_SECRET_ACCESS_KEY=...`
- `AWS_DEFAULT_REGION=us-east-1` (o tu región)
- `AWS_BUCKET=tu-bucket`
- `AWS_USE_PATH_STYLE_ENDPOINT=false`
- `AWS_ENDPOINT=` (vacío si es AWS real; se usa para MinIO/compatibles)

Opcional (si usas CloudFront):
- `AWS_URL=https://cdn.tudominio.com`

### 1.3 Configuración del disco S3

Archivo: `config/filesystems.php`

Verifica/ajusta que exista el disco `s3` (Laravel normalmente lo trae). Lo importante:
- `key`, `secret`, `region`, `bucket`
- `url` (si quieres URL base)
- `visibility` (público/privado según tu caso)

Recomendación práctica:
- Bucket **privado** + URLs firmadas para avatares/obras (mejor seguridad), o
- Bucket **público** solo para obras públicas (más simple).

### 1.4 Enlace `storage:link`

Cuando migres a S3, **ya no dependes** del symlink `public/storage` para esas imágenes.
Puedes mantenerlo si aún guardas algo local (logs, archivos temporales, etc.).

---

## 2) Cambios en el código para guardar y mostrar imágenes desde S3

### 2.1 Definir “discos” por tipo de archivo

Para no cambiar el filesystem completo de golpe, recomiendo usar discos explícitos:
- `artworks` → S3
- `avatars` → S3

Implementación (opción simple):
- Seguir usando el disco `s3` pero con prefijos:
  - `artworks/...`
  - `avatars/...`

### 2.2 Actualizar subida de imágenes de obras

Archivo a actualizar:
- `app/Http/Controllers/ArtworkController.php`

Cambio conceptual:
- Antes: `$request->file('image')->storeAs('artworks', $fileName, 'public')`
- Después (S3): `$request->file('image')->storeAs('artworks', $fileName, 's3')`

Notas:
- `image_path` en DB puede seguir guardando `artworks/archivo.jpg` (funciona para ambos).
- Si usas bucket privado, en UI debes usar URL firmada (ver 2.5).

### 2.3 Actualizar subida de avatares

Archivos:
- `app/Http/Controllers/ArtistProfileController.php`
- `app/Http/Controllers/VisitorProfileController.php`

Cambio:
- Antes: `storeAs('avatars', $fileName, 'public')` / `store('avatars', 'public')`
- Después: `storeAs('avatars', $fileName, 's3')` / `store('avatars', 's3')`

### 2.4 Eliminar archivos al borrar/actualizar

Archivo:
- `app/Http/Controllers/ArtworkController.php` (borrado de obra / reemplazo de imagen)

Cambio:
- Antes: `Storage::disk('public')->delete($path)`
- Después: `Storage::disk('s3')->delete($path)`

Igual para avatares en sus controladores.

### 2.5 Mostrar imágenes en la UI (Blade)

Archivos que suelen requerir ajuste:
- `resources/views/visitor/dashboard.blade.php` (usa `Storage::url` en avatar)
- `resources/views/artworks/index.blade.php` (usa `asset('storage/' . $artwork->image_path)`)
- `resources/views/artworks/show.blade.php`
- `resources/views/artist/*.blade.php` y `resources/views/artists/index.blade.php`

Recomendación: **unificar** y no “armar URLs a mano”.

Opción A (bucket público / `AWS_URL` configurado):
- Usar siempre: `Storage::disk('s3')->url($path)`

Opción B (bucket privado):
- Usar URLs firmadas (temporales):
  - `Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(10))`

Como `temporaryUrl()` no es ideal llamarlo directamente en Blade (se recalcula en cada render), se recomienda:
- Crear un helper/servicio “URL resolver” (ver 5. Arquitectura Hexagonal) o
- Calcular la URL en el Controller y pasarla a la vista.

### 2.6 Migración de archivos existentes (local → S3)

Si ya tienes imágenes en `storage/app/public/...`, debes subirlas a S3.

Ruta recomendada (script Artisan):
1) Crear un comando:
   - `php artisan make:command MigrateLocalMediaToS3`
2) Recorrer registros:
   - `artworks.image_path`
   - `users.avatar_path`
3) Por cada path:
   - leer desde `Storage::disk('public')`
   - escribir a `Storage::disk('s3')`
   - (opcional) verificar hash/tamaño
   - (opcional) borrar local si todo ok

---

## 3) Checklist de S3 (permisos/seguridad)

### Bucket público (simple)
- Política para permitir `s3:GetObject` en `artworks/*` y/o `avatars/*` si deseas.
- Cuidado: si avatares deben ser privados, no uses esta opción.

### Bucket privado (recomendado)
- Bloquear acceso público.
- Generar URL temporal (`temporaryUrl`) para mostrar archivos.
- Si usas CloudFront:
  - firmar URLs con CloudFront (otra estrategia), o
  - seguir con `temporaryUrl` + `AWS_URL` no aplica.

---

## 4) Ruta de trabajo para migrar a Arquitectura Hexagonal (paso a paso)

Meta: separar el proyecto por capas:
- **Dominio** (reglas del negocio, entidades, lógica pura)
- **Aplicación** (casos de uso / servicios de aplicación)
- **Infraestructura** (DB, S3, APIs externas)
- **Entradas/Adapters** (HTTP Controllers, CLI Commands, Jobs)

### 4.1 Propuesta de estructura de carpetas (Laravel-friendly)

Dentro de `app/` (para no pelear con autoload), crear:

- `app/Domain/`
  - `Artworks/`
  - `Users/`
  - `Social/` (likes, comments, followers)
  - `Reports/`
  - `Books/`
- `app/Application/`
  - `Artworks/` (UseCases)
  - `Users/`
  - `Social/`
- `app/Infrastructure/`
  - `Persistence/` (repositorios Eloquent/DB)
  - `Storage/` (S3)
  - `Integrations/GoogleBooks/`
  - `Pdf/`
- `app/Adapters/`
  - `Http/` (Controllers “delgados” o Request mappers)
  - `Cli/` (comandos)

> Nota: puedes mantener `app/Http/Controllers` al inicio y migrarlos gradualmente.

### 4.2 Definir puertos (interfaces) primero

Crear interfaces en `app/Application/.../Ports` (o `Contracts`) para desacoplar:

1) **Repositorio de obras**
   - `ArtworkRepository` (guardar, actualizar, listar públicas, etc.)
2) **Almacenamiento de archivos**
   - `MediaStorage` (put/getUrl/delete)
3) **Servicio de recomendaciones de libros**
   - `BookSearchService`
4) **Generación de PDF**
   - `PublicationsReportGenerator`

### 4.3 Implementar adaptadores (Infraestructura)

Implementaciones:
- `EloquentArtworkRepository` en `app/Infrastructure/Persistence/...`
- `S3MediaStorage` en `app/Infrastructure/Storage/...` usando `Storage::disk('s3')`
- `GoogleBooksSearchService` en `app/Infrastructure/Integrations/GoogleBooks/...`
- `DomPdfPublicationsReportGenerator` en `app/Infrastructure/Pdf/...`

### 4.4 Casos de uso (Application)

Ejemplos (por carpeta):
- `app/Application/Artworks/CreateArtworkUseCase.php`
- `app/Application/Artworks/UpdateArtworkUseCase.php`
- `app/Application/Artworks/DeleteArtworkUseCase.php`
- `app/Application/Social/ToggleLikeUseCase.php`
- `app/Application/Social/ToggleFollowUseCase.php`

Los controllers pasarán a:
- validar request (FormRequest)
- mapear DTO
- ejecutar use case
- retornar respuesta (view/json)

### 4.5 Migración incremental (orden recomendado)

1) **Artworks** (porque toca imágenes + reglas)
   - Extraer upload + persistencia + permisos.
2) **MediaStorage (S3)** como puerto/adaptador
3) **Likes/Comments/Followers** (endpoints JSON)
4) **Books** (integración externa)
5) **Reports** (PDF)
6) **Dashboard agregaciones** (queries)

### 4.6 Validación con pruebas

Antes de mover lógica:
- Crear feature tests mínimos para “crear obra”, “listar galería”, “ver perfil artista”.

Después:
- Mantener tests pasando mientras migras internamente.

Herramientas:
- PHPUnit/Pest
- `Storage::fake('s3')` en tests para no tocar AWS real.

---

## 5) Ruta “exacta” de archivos a tocar para S3 (en este proyecto)

1) Dependencias:
- `composer.json` (se modifica al instalar `league/flysystem-aws-s3-v3`)

2) Config:
- `.env` (variables AWS)
- `config/filesystems.php` (disco `s3` y/o `FILESYSTEM_DISK`)

3) Upload/borrado de obras:
- `app/Http/Controllers/ArtworkController.php`

4) Upload/borrado de avatares:
- `app/Http/Controllers/ArtistProfileController.php`
- `app/Http/Controllers/VisitorProfileController.php`

5) Render de imágenes:
- `resources/views/artworks/index.blade.php`
- `resources/views/artworks/show.blade.php`
- `resources/views/visitor/dashboard.blade.php`
- `resources/views/artist/profile.blade.php`
- `resources/views/artist/profile-public.blade.php`
- `resources/views/artist/profile-edit.blade.php`
- `resources/views/artists/index.blade.php`

6) (Opcional) Migración local→S3:
- `app/Console/Commands/MigrateLocalMediaToS3.php` (nuevo)

---

## 6) Recomendación final (para evitar problemas en producción)

- Usar **S3** para archivos y **bucket privado** si hay cualquier contenido no público.
- En dev, usar `Storage::fake('s3')` en tests y/o usar un bucket “dev”.
- Separar migraciones (DB) usando host Neon **directo** (no pooler), y app runtime usando pooler si lo necesitas.

