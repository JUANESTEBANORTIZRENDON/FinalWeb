<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">Detalle de usuario</h1>
                <p class="text-muted mb-0">Información general y estado.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    Volver
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3 text-center">
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                                <span class="text-primary fw-bold" style="font-size: 2.5rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            @if($user->is_active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Rol</p>
                                    <strong class="text-capitalize">{{ $user->user_type }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Registro</p>
                                    <strong>{{ $user->created_at?->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Website</p>
                                    <strong>{{ $user->website_url ?? 'No especificado' }}</strong>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-2">Bio</h5>
                        <p class="text-muted">{{ $user->bio ?? 'Sin biografía.' }}</p>

                        <h5 class="mb-2 mt-4">Redes sociales</h5>
                        <ul class="list-unstyled text-muted mb-0">
                            <li>Instagram: {{ $user->social_media['instagram'] ?? 'No especificado' }}</li>
                            <li>Twitter: {{ $user->social_media['twitter'] ?? 'No especificado' }}</li>
                            <li>Facebook: {{ $user->social_media['facebook'] ?? 'No especificado' }}</li>
                        </ul>
                    </div>
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
