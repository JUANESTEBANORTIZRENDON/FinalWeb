<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">Panel de Administrador</h1>
                <p class="text-muted mb-0">Gestiona usuarios, perfiles y monitorea el feed público.</p>
            </div>
            <a href="{{ route('admin.feed.monitor') }}" class="btn btn-primary">
                <i class="fas fa-chart-line me-2"></i>Monitoreo del feed
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="mb-1">Usuarios</h5>
                                <p class="text-muted mb-0">Listado completo y filtros</p>
                            </div>
                            <span class="badge bg-primary">{{ $totalUsers }}</span>
                        </div>
                        <p class="small text-muted mb-3">Activos: {{ $activeUsers }}</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary w-100">
                            Ver usuarios
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="mb-1">Perfiles</h5>
                                <p class="text-muted mb-0">Editar datos y roles</p>
                            </div>
                            <span class="badge bg-primary">{{ $totalUsers }}</span>
                        </div>
                        <p class="small text-muted mb-3">Admin, artista o visitante</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary w-100">
                            Administrar perfiles
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="mb-1">Monitoreo</h5>
                                <p class="text-muted mb-0">Feed público y métricas</p>
                            </div>
                            <span class="badge bg-primary">{{ $publicArtworks }}</span>
                        </div>
                        <p class="small text-muted mb-3">Obras públicas: {{ $publicArtworks }}</p>
                        <a href="{{ route('admin.feed.monitor') }}" class="btn btn-primary w-100">
                            Ver monitoreo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total obras</p>
                        <h4 class="mb-0">{{ $totalArtworks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Obras públicas</p>
                        <h4 class="mb-0">{{ $publicArtworks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Likes</p>
                        <h4 class="mb-0">{{ $totalLikes }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Comentarios</p>
                        <h4 class="mb-0">{{ $totalComments }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }
        .badge.bg-primary {
            background-color: #6b21a8 !important;
        }
        .btn-primary {
            background-color: #6b21a8 !important;
            border-color: #6b21a8 !important;
        }
    </style>
</x-app-layout>
