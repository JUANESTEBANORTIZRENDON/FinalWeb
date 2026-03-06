<x-app-layout>
    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success banter-section">{{ session('status') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8 banter-section">
                <div class="banter-card p-4">
                    <h1 class="banter-title h3 mb-3">Perfil de visitante</h1>
                    <p class="text-muted mb-4">Actualiza tus datos y credenciales.</p>

                    <form method="POST" action="{{ route('visitor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center mb-4">
                            @if($user->avatar_path)
                                <img src="{{ Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder" style="width: 120px; height: 120px;">
                                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <label for="avatar" class="form-label">Imagen de perfil</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electronico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <h2 class="h5 fw-bold mt-4 mb-3">Cambiar contrasena</h2>
                        <p class="text-muted small">Deja vacio si no quieres cambiarla.</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contrasena actual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva contrasena</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar nueva contrasena</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <button type="submit" class="banter-btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 banter-section">
                <div class="banter-card p-4 h-100">
                    <h3 class="h5 fw-bold mb-3">Pasar a artista</h3>
                    <p class="text-muted mb-4">Si creas arte, publica tus obras y gestiona tu perfil profesional en BANTER.</p>
                    <form method="POST" action="{{ route('visitor.convert.artist') }}">
                        @csrf
                        <button type="submit" class="banter-btn-primary w-100">
                            <i class="fas fa-paint-brush"></i>Convertirme en artista
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
