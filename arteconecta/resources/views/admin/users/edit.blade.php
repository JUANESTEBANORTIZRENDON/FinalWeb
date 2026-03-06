<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">Editar usuario</h1>
                <p class="text-muted mb-0">Actualiza perfil, rol y estado.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">
                    Ver detalle
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    Volver
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-12 d-flex align-items-center gap-3">
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <span class="text-primary fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select name="user_type" class="form-select" required>
                            <option value="admin" @selected(old('user_type', $user->user_type) === 'admin')>Admin</option>
                            <option value="artist" @selected(old('user_type', $user->user_type) === 'artist')>Artista</option>
                            <option value="visitor" @selected(old('user_type', $user->user_type) === 'visitor')>Visitante</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" @selected(old('is_active', $user->is_active ? '1' : '0') === '1')>Activo</option>
                            <option value="0" @selected(old('is_active', $user->is_active ? '1' : '0') === '0')>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" name="website_url" value="{{ old('website_url', $user->website_url) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Instagram</label>
                        <input type="text" name="social_media[instagram]" value="{{ old('social_media.instagram', $user->social_media['instagram'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Twitter</label>
                        <input type="text" name="social_media[twitter]" value="{{ old('social_media.twitter', $user->social_media['twitter'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="social_media[facebook]" value="{{ old('social_media.facebook', $user->social_media['facebook'] ?? '') }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="col-12 d-flex justify-content-start align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar cambios
                        </button>
                    </div>
                </form>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                    <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-secondary">
                            {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('¿Seguro que quieres desactivar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            Desactivar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-primary {
            color: #6b21a8 !important;
        }
        .btn-primary {
            background-color: #6b21a8 !important;
            border-color: #6b21a8 !important;
        }
    </style>
</x-app-layout>
