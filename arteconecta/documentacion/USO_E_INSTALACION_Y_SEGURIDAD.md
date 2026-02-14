# Guía de uso, instalación y seguridad — ArteConecta

## 1. Prerrequisitos (versiones recomendadas)
- PHP 8.2.x (incluido en XAMPP 8.2 para Windows).
- Composer 2.6+.
- Node.js 18.x + npm 9.x.
- PostgreSQL (Neon o local).
- Git.
- Opcional para local: XAMPP (Apache + PHP) si prefieres no usar `php artisan serve`.

## 2. Instalación y puesta en marcha (local)
1) Clonar el repositorio  
   `git clone https://github.com/JUANESTEBANORTIZRENDON/FinalWeb.git`
2) Entrar en el proyecto  
   `cd arteconecta`
3) Copiar variables de entorno de ejemplo  
   `copy .env.example .env`  (Windows)  
   Luego completa valores (DB, AWS si aplica) y genera la APP_KEY:
   `php artisan key:generate`
4) Dependencias backend  
   `composer install`
5) Dependencias frontend  
   `npm install`
6) Migraciones (BD Postgres/Neon)  
   `php artisan migrate`
7) Enlaces de storage (si usas disco local)  
   `php artisan storage:link`
8) Ejecutar en modo dev  
   - Backend: `php artisan serve`
   - Frontend assets (watch): `npm run dev`
   - Build producción: `npm run build`

## 3. Uso rápido
- Registro y login (roles: artista / visitante).
- Galería: `/artworks`
- Perfil artista: `/my-profile` (requiere rol artista).
- Dashboard visitante: `/dashboard`
- Subida de obra: `/my-artworks/create`

## 4. Configuración de imágenes (local vs S3)
- Local (por defecto): `FILESYSTEM_DISK=public` y `php artisan storage:link`.
- S3: setear variables AWS en `.env` y usar disco `s3` para uploads (ver documento `S3_Y_ARQUITECTURA_HEXAGONAL_RUTA_DE_TRABAJO.md`).

## 5. Pruebas
- Pruebas de aplicación: `php artisan test`
- Para assets: `npm run build` (verifica que compila sin errores).

## 6. Pautas de seguridad (repositorio y despliegue)
- No commitear `.env` ni credenciales; `.gitignore` ya lo excluye.
- Usar secretos en el proveedor (GitHub Actions, Render, etc.).
- `APP_DEBUG=false` en producción; `APP_ENV=production`.
- Conexión DB vía SSL (`DB_SSLMODE=require` en Neon).
- Separar hosts: usar host directo para migraciones y pooler para runtime si es necesario.
- Rotar credenciales y claves (APP_KEY, DB, AWS) ante cualquier exposición.
- Mínimos permisos: el usuario DB solo con privilegios sobre el esquema de la app.
- Revisar dependencias periódicamente (`composer update`, `npm audit fix` con criterio).

## 7. Archivos sensibles / gitignore
- `.env` y variantes (`.env.*`) deben permanecer fuera del repo (ya en `.gitignore`).
- No subir dumps de BD ni claves privadas.

## 8. Troubleshooting rápido
- Imágenes no cargan en local: verificar `php artisan storage:link` y que `image_path` exista.
- Error de boolean en Neon: asegurarse de usar host adecuado y, si se usa pooler, comparar booleanos con `'true'/'false'` (ya corregido en controladores).
- Migraciones: usar variables DB en `.env`; si falla la extensión UUID, ejecuta `CREATE EXTENSION "uuid-ossp"` con un rol con permisos.

