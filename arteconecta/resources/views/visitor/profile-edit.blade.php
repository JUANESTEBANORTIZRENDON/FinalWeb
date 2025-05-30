<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Mensajes de estado o error -->
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">Editar perfil</h2>
                    </div>
                    <div class="card-body">
                        <!-- Formulario de información de perfil -->
                        <form method="POST" action="{{ route('visitor.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <!-- Avatar actual y formulario para cambiarlo -->
                                    <div class="avatar-container mb-3">
                                        @if($user->avatar_path)
                                            <img src="{{ Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" 
                                                class="rounded-circle img-thumbnail mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="avatar-placeholder mb-2 mx-auto" 
                                                style="width: 150px; height: 150px; background-color: #f3e8ff; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                <span style="font-size: 4rem; color: #6b21a8;">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="mt-2">
                                            <label for="avatar" class="form-label text-muted small">Cambiar imagen de perfil</label>
                                            <input type="file" class="form-control form-control-sm" id="avatar" name="avatar" accept="image/*">
                                            @error('avatar')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <!-- Información básica -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h4 class="h5 mb-3 border-bottom pb-2">Cambiar contraseña</h4>
                            <p class="text-muted small mb-3">Deja estos campos en blanco si no deseas cambiar tu contraseña</p>

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Contraseña actual</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sección para convertirse en artista -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="h4 mb-0">Convertirse en artista</h3>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <h4 class="h5">¿Eres un artista?</h4>
                                <p class="text-muted mb-md-0">
                                    Comparte tus obras con nuestra comunidad y conecta con otros artistas y admiradores.
                                    Cambiar a una cuenta de artista te permitirá subir tus propias obras, recibir comentarios y seguidores.
                                </p>
                            </div>
                            <div class="col-md-3 text-center text-md-end">
                                <form method="POST" action="{{ route('visitor.convert.artist') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paint-brush me-2"></i>Convertirse en artista
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
