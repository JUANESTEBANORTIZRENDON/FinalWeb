<x-app-layout>
    <div class="container py-5">
        <div class="row g-4">
            <!-- Encabezado -->
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-4">
                            @if($user->avatar_path)
                                <img src="{{ Storage::url($user->avatar_path) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text={{ substr($user->name, 0, 1) }}'; this.style.backgroundColor='#f3e8ff';">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px; background-color: #f3e8ff; color: #6b21a8;">
                                    <span style="font-size: 2rem;">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="h3 fw-bold mb-1">¡Bienvenido, {{ $user->name }}!</h1>
                            <p class="text-muted mb-0">Descubre las últimas obras, sigue artistas y comparte tus opiniones.</p>
                            
                            <div class="mt-3">
                                <span class="badge bg-secondary p-2">
                                    <i class="fas fa-user me-1"></i> Visitante
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 1: Obras recientes -->
            <div class="col-12">
                <div class="section-container section-recent p-4 rounded">
                    <h2 class="h4 fw-bold mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clock me-2"></i>Obras recientes</span>
                        <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </h2>
                
                @if($recentArtworks->count() > 0)
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        @foreach($recentArtworks as $artwork)
                            <div class="col">
                                <div class="card h-100 shadow-sm artwork-card">
                                    <div style="height: 200px; overflow: hidden;">
                                        @if($artwork->image_path)
                                            <img src="{{ Storage::url($artwork->image_path) }}" class="card-img-top" alt="{{ $artwork->title }}" style="object-fit: cover; height: 100%; width: 100%;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3 class="h5 card-title">
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none text-dark">{{ $artwork->title }}</a>
                                        </h3>
                                        <p class="text-primary small mb-1">
                                            <i class="fas fa-user-circle me-1"></i> 
                                            <a href="{{ route('artist.profile.public', $artwork->artist->id) }}" class="text-decoration-none">{{ $artwork->artist->name }}</a>
                                        </p>
                                        @if($artwork->category)
                                            <p class="text-secondary small mb-2">
                                                <i class="fas fa-tag me-1"></i> {{ $artwork->category->name }}
                                            </p>
                                        @endif
                                        
                                        <!-- Interacciones -->
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="d-flex gap-3">
                                                <span title="Me gusta">
                                                    <i class="fas fa-heart text-danger me-1"></i> {{ $artwork->likes->count() }}
                                                </span>
                                                <span title="Comentarios">
                                                    <i class="fas fa-comment text-primary me-1"></i> {{ $artwork->comments->count() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No hay obras recientes disponibles.
                    </div>
                @endif
                </div>
            </div>

            <!-- Sección 2: Obras de artistas seguidos -->
            <div class="col-12 mt-5">
                <div class="section-container section-following p-4 rounded">
                    <h2 class="h4 fw-bold mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2"></i>Artistas que sigues</span>
                        <a href="{{ route('artists.index') }}" class="btn btn-sm btn-outline-primary">Explorar artistas</a>
                    </h2>
                
                @if($followedArtistsArtworks->count() > 0)
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        @foreach($followedArtistsArtworks as $artwork)
                            <div class="col">
                                <div class="card h-100 shadow-sm artwork-card">
                                    <div style="height: 200px; overflow: hidden;">
                                        @if($artwork->image_path)
                                            <img src="{{ Storage::url($artwork->image_path) }}" class="card-img-top" alt="{{ $artwork->title }}" style="object-fit: cover; height: 100%; width: 100%;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3 class="h5 card-title">
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none text-dark">{{ $artwork->title }}</a>
                                        </h3>
                                        <p class="text-primary small mb-1">
                                            <i class="fas fa-user-circle me-1"></i> 
                                            <a href="{{ route('artist.profile.public', $artwork->artist->id) }}" class="text-decoration-none">{{ $artwork->artist->name }}</a>
                                        </p>
                                        @if($artwork->category)
                                            <p class="text-secondary small mb-2">
                                                <i class="fas fa-tag me-1"></i> {{ $artwork->category->name }}
                                            </p>
                                        @endif
                                        
                                        <!-- Interacciones -->
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="d-flex gap-3">
                                                <span title="Me gusta">
                                                    <i class="fas fa-heart text-danger me-1"></i> {{ $artwork->likes->count() }}
                                                </span>
                                                <span title="Comentarios">
                                                    <i class="fas fa-comment text-primary me-1"></i> {{ $artwork->comments->count() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center p-5">
                        <i class="fas fa-user-friends fa-3x mb-3"></i>
                        <h4>Aún no sigues a ningún artista</h4>
                        <p>Sigue a artistas para ver sus obras más recientes aquí.</p>
                        <a href="{{ route('artists.index') }}" class="btn btn-primary mt-2">Explorar artistas</a>
                    </div>
                @endif
            </div>

            <!-- Sección 3: Obras favoritas (me gusta) -->
            <div class="col-12 mt-5">
                <div class="section-container section-liked p-4 rounded">
                    <h2 class="h4 fw-bold mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-heart me-2"></i>Obras que te han gustado</span>
                        <a href="{{ route('artworks.index') }}" class="btn btn-sm btn-outline-primary">Explorar más</a>
                    </h2>
                
                @if($likedArtworks->count() > 0)
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        @foreach($likedArtworks as $artwork)
                            <div class="col">
                                <div class="card h-100 shadow-sm artwork-card">
                                    <div style="height: 200px; overflow: hidden;">
                                        @if($artwork->image_path)
                                            <img src="{{ Storage::url($artwork->image_path) }}" class="card-img-top" alt="{{ $artwork->title }}" style="object-fit: cover; height: 100%; width: 100%;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h3 class="h5 card-title">
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none text-dark">{{ $artwork->title }}</a>
                                        </h3>
                                        <p class="text-primary small mb-1">
                                            <i class="fas fa-user-circle me-1"></i> 
                                            <a href="{{ route('artist.profile.public', $artwork->artist->id) }}" class="text-decoration-none">{{ $artwork->artist->name }}</a>
                                        </p>
                                        @if($artwork->category)
                                            <p class="text-secondary small mb-2">
                                                <i class="fas fa-tag me-1"></i> {{ $artwork->category->name }}
                                            </p>
                                        @endif
                                        
                                        <!-- Interacciones -->
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="d-flex gap-3">
                                                <span title="Me gusta">
                                                    <i class="fas fa-heart text-danger me-1"></i> {{ $artwork->likes->count() }}
                                                </span>
                                                <span title="Comentarios">
                                                    <i class="fas fa-comment text-primary me-1"></i> {{ $artwork->comments->count() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center p-5">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h4>Sin favoritos todavía</h4>
                        <p>Cuando marques obras con "Me gusta", aparecerán aquí para un acceso rápido.</p>
                        <a href="{{ route('artworks.index') }}" class="btn btn-primary mt-2">Explorar la galería</a>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos para las tarjetas de obras */
        .artwork-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .artwork-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Estilos para los contenedores de sección */
        .section-container {
            border-left: 5px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        /* Sección de obras recientes - Azul suave */
        .section-recent {
            background-color: #e8f4ff;
            border-left-color: #4dabf7;
        }
        
        .section-recent h2 span {
            color: #1c7ed6;
        }
        
        /* Sección de artistas seguidos - Verde suave */
        .section-following {
            background-color: #e6f7ef;
            border-left-color: #40c997;
        }
        
        .section-following h2 span {
            color: #099268;
        }
        
        /* Sección de obras que te han gustado - Rosa suave */
        .section-liked {
            background-color: #fff0f6;
            border-left-color: #f783ac;
        }
        
        .section-liked h2 span {
            color: #e64980;
        }
    </style>
</x-app-layout>
