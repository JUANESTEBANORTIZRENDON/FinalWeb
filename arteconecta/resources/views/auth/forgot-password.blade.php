<x-guest-layout>
    <!-- Título de la página -->
    <h2 class="text-center mb-4 fw-bold">Recuperar contraseña</h2>

    <div class="alert alert-info mb-4">
        ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva contraseña.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('login') }}" class="btn-link">
                Volver al inicio de sesión
            </a>
            <button type="submit" class="btn btn-primary">
                Enviar enlace de recuperación
            </button>
        </div>
    </form>
</x-guest-layout>
