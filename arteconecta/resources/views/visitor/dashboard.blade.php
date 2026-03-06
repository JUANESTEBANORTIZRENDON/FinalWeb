<x-app-layout>
    <div class="container py-4">
        <section class="banter-card p-4 mb-4 banter-section">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div>
                    @if($user->avatar_path)
                        <img src="{{ Storage::url($user->avatar_path) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 92px; height: 92px; object-fit: cover;">
                    @else
                        <div class="avatar-placeholder" style="width: 92px; height: 92px;">
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h1 class="banter-title h2 mb-1">Hola, {{ $user->name }}</h1>
                    <p class="text-muted mb-0">Tu panel personal para descubrir arte, seguir artistas y guardar favoritos.</p>
                </div>
                <div>
                    <a href="{{ route('artists.index') }}" class="banter-btn-primary">Explorar artistas</a>
                </div>
            </div>
        </section>

        <section class="banter-section mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="banter-title h4 mb-0">Historial reciente</h2>
                <a href="{{ route('artworks.index') }}" class="banter-btn-outline">Ver galeria</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse($recentArtworks as $artwork)
                    <div class="col">
                        <article class="banter-card h-100 overflow-hidden">
                            <img src="{{ $artwork->image_path ? Storage::url($artwork->image_path) : 'https://via.placeholder.com/640x440?text=Sin+imagen' }}" alt="{{ $artwork->title }}" class="w-100" style="height: 220px; object-fit: cover;">
                            <div class="p-3">
                                <h3 class="h5 fw-bold mb-1">{{ $artwork->title }}</h3>
                                <p class="text-muted small mb-2">Por {{ $artwork->artist->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small"><i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }} <i class="fas fa-comment text-primary ms-2 me-1"></i>{{ $artwork->comments_count }}</span>
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="banter-btn-primary btn-sm">Ver</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="banter-card p-4 text-center">Sin actividad reciente por ahora.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="banter-section mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="banter-title h4 mb-0">Siguiendo</h2>
                <a href="{{ route('artists.index') }}" class="banter-btn-outline">Buscar artistas</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse($followedArtistsArtworks as $artwork)
                    <div class="col">
                        <article class="banter-card h-100 overflow-hidden">
                            <img src="{{ $artwork->image_path ? Storage::url($artwork->image_path) : 'https://via.placeholder.com/640x440?text=Sin+imagen' }}" alt="{{ $artwork->title }}" class="w-100" style="height: 220px; object-fit: cover;">
                            <div class="p-3">
                                <h3 class="h5 fw-bold mb-1">{{ $artwork->title }}</h3>
                                <p class="text-muted small mb-2">{{ $artwork->artist->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small"><i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }} <i class="fas fa-comment text-primary ms-2 me-1"></i>{{ $artwork->comments_count }}</span>
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="banter-btn-primary btn-sm">Ver</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="banter-card p-4 text-center">
                            Aun no sigues artistas.
                            <div class="mt-3"><a href="{{ route('artists.index') }}" class="banter-btn-primary">Empezar a seguir</a></div>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="banter-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="banter-title h4 mb-0">Favoritos</h2>
                <a href="{{ route('artworks.index') }}" class="banter-btn-outline">Guardar mas</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse($likedArtworks as $artwork)
                    <div class="col">
                        <article class="banter-card h-100 overflow-hidden">
                            <img src="{{ $artwork->image_path ? Storage::url($artwork->image_path) : 'https://via.placeholder.com/640x440?text=Sin+imagen' }}" alt="{{ $artwork->title }}" class="w-100" style="height: 220px; object-fit: cover;">
                            <div class="p-3">
                                <h3 class="h5 fw-bold mb-1">{{ $artwork->title }}</h3>
                                <p class="text-muted small mb-2">{{ $artwork->artist->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small"><i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }} <i class="fas fa-comment text-primary ms-2 me-1"></i>{{ $artwork->comments_count }}</span>
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="banter-btn-primary btn-sm">Ver</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="banter-card p-4 text-center">
                            Todavia no tienes favoritos guardados.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
