<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">Monitoreo del feed</h1>
                <p class="text-muted mb-0">Vista pública + métricas clave.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('artworks.index') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="fas fa-eye me-2"></i>Ver como visitante
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    Volver
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total obras públicas</p>
                        <h4 class="mb-0">{{ $totalPublicArtworks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Nuevas (últimos 7 días)</p>
                        <h4 class="mb-0">{{ $newPublicArtworks }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Likes totales</p>
                        <h4 class="mb-0">{{ $totalLikes }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Comentarios totales</p>
                        <h4 class="mb-0">{{ $totalComments }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Top 5 obras con más likes</h5>
                        <ul class="list-group list-group-flush">
                            @forelse($topArtworks as $artwork)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $artwork->title }}</strong>
                                        <div class="text-muted small">Por {{ $artwork->artist?->name }}</div>
                                    </div>
                                    <span class="badge bg-primary">{{ $artwork->likes_count }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">No hay datos disponibles.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Últimos comentarios</h5>
                        <ul class="list-group list-group-flush">
                            @forelse($recentComments as $comment)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $comment->user?->name }}</strong>
                                            <div class="text-muted small">{{ $comment->created_at?->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <a href="{{ route('artworks.show', $comment->artwork_id) }}" class="btn btn-sm btn-outline-secondary">
                                            Ver obra
                                        </a>
                                    </div>
                                    <p class="mb-1 mt-2">{{ Str::limit($comment->content, 120) }}</p>
                                    <div class="text-muted small">{{ $comment->artwork?->title }}</div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">No hay comentarios recientes.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Feed público</h5>
                @if($feedArtworks->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($feedArtworks as $artwork)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $artwork->image_path) }}" class="card-img-top" alt="{{ $artwork->title }}" style="height: 200px; object-fit: cover;">
                                        @if($artwork->category)
                                            <span class="position-absolute top-0 end-0 badge bg-info m-2">
                                                {{ $artwork->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $artwork->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            Por {{ $artwork->artist?->name }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">
                                                <i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }}
                                                <i class="fas fa-comment text-secondary ms-2 me-1"></i>{{ $artwork->comments_count }}
                                            </span>
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-primary">
                                                Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $feedArtworks->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        No hay obras públicas disponibles.
                    </div>
                @endif
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
        .badge.bg-primary {
            background-color: #6b21a8 !important;
        }
        .card {
            border-radius: 12px;
        }
    </style>
</x-app-layout>
