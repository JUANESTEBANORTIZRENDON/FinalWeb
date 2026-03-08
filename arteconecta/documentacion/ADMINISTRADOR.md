# Administrador - Creación, manejo y edición

Este proyecto usa un sistema de roles basado en el campo `user_type` del modelo **User**.

- `admin` → Administrador (acceso al panel de administración)
- `artist` → Artista (puede subir obras, generar reportes, etc.)
- `visitor` → Visitante (usuarios normales)

> En el registro estándar (`/register`) solo se puede escoger **artist** o **visitor**.

---

## 1) ¿Ya existe un apartado para crear administradores?

Sí y no:

- **Sí:** Existen rutas y vistas para administrar usuarios (`/admin/users`) y cambiar su rol (`user_type`).
- **No:** No hay un formulario público para *crear* un admin desde cero; el sistema asume que el primer admin se crea manualmente (base de datos, seeder o tinker).

Por eso es necesario crear el primer administrador directamente en la base de datos o mediante un seeder/tinker.

---

## 2) Usuario administrador ya creado (en esta instancia)

Se creó un usuario administrador con estas credenciales de ejemplo (cámbialas inmediatamente):

- **Email:** `admin@arteconecta.test`
- **Contraseña:** `Admin123!`

> Nota: Si no quieres usar estas credenciales en producción, puedes cambiarlas desde el panel de administración (o con Tinker/SQL).

---

## 3) Crear un administrador (método recomendado: Tinker)

Ejecuta este comando desde la raíz del proyecto:

```powershell
& "C:\xampp\php\php.exe" artisan tinker --execute="use App\Models\User; use Illuminate\Support\Facades\Hash; use Illuminate\Support\Facades\DB; User::updateOrCreate(['email'=>'admin@arteconecta.test'], ['name'=>'Administrador', 'user_type'=>'admin', 'password'=>Hash::make('Admin123!'), 'is_active'=>DB::raw('true'), 'email_verified_at'=>now()]);"
```

Esto crea (o actualiza) un usuario con rol `admin`, activo y con el email verificado.

---

## 4) Cómo acceder al panel de administración

1. Inicia sesión con un usuario `admin`.
2. Navega a:
   - Panel principal: `/admin`
   - Gestión de usuarios: `/admin/users`
   - Monitor de feed: `/admin/feed-monitor`

Si intentas acceder sin ser admin, serás redirigido al login.

---

## 5) Cómo editar un administrador (o cualquier usuario)

1. Desde el panel de admin, ve a **Usuarios** (`/admin/users`).
2. Busca al usuario y haz clic en **Editar**.
3. En el formulario puedes:
   - Cambiar nombre, email, bio, redes sociales.
   - Cambiar rol (`user_type`) entre: **admin**, **artist**, **visitor**.
   - Activar/desactivar el usuario (campo `is_active`).
   - Cambiar contraseña (si dejas vacía la contraseña, no se modifica).

### Restricciones importantes (ya aplicadas en el controlador)

- Un administrador **no puede** quitarse el rol `admin` a sí mismo.
- Un administrador **no puede** desactivarse a sí mismo.

---

## 6) Opcional: crear admin via SQL (postgres)

Si prefieres usar SQL, puedes ejecutar algo como:

```sql
INSERT INTO users (id, name, email, password, user_type, is_active, email_verified_at, created_at, updated_at)
VALUES (gen_random_uuid(), 'Administrador', 'admin@arteconecta.test', '<hash_password>', 'admin', true, now(), now(), now());
```

> Nota: `password` debe ser el hash generado por `bcrypt()` / `Hash::make()`.

---

## 7) Cómo saber si un usuario es administrador en el código

En el modelo `App\Models\User` existe el método:

```php
public function isAdmin()
{
    return $this->user_type === 'admin';
}
```

Y el middleware `App\Http\Middleware\AdminMiddleware` protege las rutas de `/admin`.

---

## 8) Recomendación de seguridad

- Cambia la contraseña del administrador tan pronto como puedas.
- No comites credenciales ni `.env`.
- Usa `APP_DEBUG=false` en producción.
- Mantén `DB_SSLMODE=require` si usas Neon/Postgres.
