<x-guest-layout>
    <!-- Título de la página -->
    <h2 class="text-center mb-4 fw-bold">Regístrate en ArteConecta</h2>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nombre completo</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- User Type -->
        <div class="mb-3">
            <label for="user_type" class="form-label">Tipo de usuario</label>
            <select id="user_type" class="form-select @error('user_type') is-invalid @enderror" name="user_type" required>
                <option value="" disabled {{ old('user_type') ? '' : 'selected' }}>Selecciona tipo de usuario</option>
                <option value="artist" {{ old('user_type') == 'artist' ? 'selected' : '' }}>Artista</option>
                <option value="visitor" {{ old('user_type') == 'visitor' ? 'selected' : '' }}>Visitante</option>
            </select>
            @error('user_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Bio -->
        <div class="mb-3">
            <label for="bio" class="form-label">Biografía</label>
            <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio') }}</textarea>
            @error('bio')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Website URL -->
        <div class="mb-3">
            <label for="website_url" class="form-label">Sitio web (opcional)</label>
            <input id="website_url" type="url" class="form-control @error('website_url') is-invalid @enderror" name="website_url" value="{{ old('website_url') }}" placeholder="https://ejemplo.com">
            @error('website_url')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('login') }}" class="btn-link">
                ¿Ya tienes una cuenta?
            </a>

            <button type="submit" class="btn btn-primary">
                Registrarme
            </button>
        </div>
    </form>
</x-guest-layout>
