<x-guest-layout>
    <!-- Título de la página -->
    <h2 class="text-center mb-4 fw-bold">Iniciar sesión</h2>
    
    <!-- Mensajes de estado de la sesión -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">Recordarme</label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            @if (Route::has('password.request'))
                <a class="btn-link" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                Iniciar sesión
            </button>
        </div>
        
        <!-- Enlace a registro -->
        <div class="text-center mt-4">
            <p class="mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" class="btn-link">Regístrate aquí</a></p>
        </div>
    </form>
</x-guest-layout>
