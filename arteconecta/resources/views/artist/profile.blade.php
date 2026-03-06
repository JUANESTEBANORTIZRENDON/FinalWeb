<x-app-layout>
    @php
        $initials = collect(preg_split('/\s+/', trim($artist->name ?? '')))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        $instagram = $artist->social_media['instagram'] ?? null;
        $twitter = $artist->social_media['twitter'] ?? null;
        $facebook = $artist->social_media['facebook'] ?? null;

        $instagramUrl = $instagram ? (str_starts_with($instagram, 'http') ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@')) : null;
        $twitterUrl = $twitter ? (str_starts_with($twitter, 'http') ? $twitter : 'https://twitter.com/' . ltrim($twitter, '@')) : null;
        $facebookUrl = $facebook ? (str_starts_with($facebook, 'http') ? $facebook : 'https://facebook.com/' . ltrim($facebook, '@')) : null;

        $websiteUrl = $artist->website_url ?? null;
        if ($websiteUrl && !str_starts_with($websiteUrl, 'http')) {
            $websiteUrl = 'https://' . $websiteUrl;
        }

        $totalLikes = $artworks->sum('likes_count');
        $totalComments = $artworks->sum('comments_count');
        $totalPublic = $artworks->where('is_public', true)->count();
        $totalPrivate = $artworks->where('is_public', false)->count();
    @endphp

    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success banter-section">{{ session('status') }}</div>
        @endif

        <section class="banter-card p-4 p-lg-5 mb-4 banter-section">
            <div class="row g-4 align-items-center">
                <div class="col-lg-2 text-center">
                    @if($artist->avatar_path)
                        <img src="{{ asset('storage/' . $artist->avatar_path) }}" alt="{{ $artist->name }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="avatar-placeholder mx-auto" style="width: 150px; height: 150px;">
                            <span>{{ $initials ?: 'AR' }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <h1 class="banter-title display-6">{{ $artist->name }}</h1>
                    <p class="text-muted mb-2">Artista en BANTER</p>
                    <p class="mb-0">{{ $artist->bio ?: 'Todavia no agregas una biografia.' }}</p>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('artist.profile.edit') }}" class="banter-btn-primary">Editar perfil</a>
                        <a href="{{ route('artworks.create') }}" class="banter-btn-outline">Crear obra</a>
                        <a href="{{ route('artist.publications.report') }}" target="_blank" class="banter-btn-outline">Reporte</a>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                @if($instagramUrl)<a class="banter-btn-outline btn-sm" href="{{ $instagramUrl }}" target="_blank"><i class="fab fa-instagram"></i>Instagram</a>@endif
                @if($twitterUrl)<a class="banter-btn-outline btn-sm" href="{{ $twitterUrl }}" target="_blank"><i class="fab fa-twitter"></i>Twitter</a>@endif
                @if($facebookUrl)<a class="banter-btn-outline btn-sm" href="{{ $facebookUrl }}" target="_blank"><i class="fab fa-facebook"></i>Facebook</a>@endif
                @if($websiteUrl)<a class="banter-btn-outline btn-sm" href="{{ $websiteUrl }}" target="_blank"><i class="fas fa-globe"></i>Sitio</a>@endif
            </div>
        </section>

        <section class="banter-section" data-banter-tabs>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="banter-btn-outline active" data-banter-tab="obras" aria-selected="true">Obras</button>
                <button type="button" class="banter-btn-outline" data-banter-tab="info" aria-selected="false">Informacion</button>
                <button type="button" class="banter-btn-outline" data-banter-tab="actividad" aria-selected="false">Actividad</button>
            </div>

            <div data-banter-tab-panel="obras">
                <div class="banter-card p-3 mb-4" data-banter-slider>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 fw-bold mb-0">Carrusel de obras</h2>
                        <div class="d-flex gap-2">
                            <button type="button" class="banter-slider-btn" data-banter-prev aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="banter-slider-btn" data-banter-next aria-label="Siguiente"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="banter-scroll-track" data-banter-track>
                        @forelse($artworks as $artwork)
                            <article class="banter-card overflow-hidden" data-banter-slide>
                                <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none">
                                    <img src="{{ $artwork->image_path ? asset('storage/' . $artwork->image_path) : 'https://via.placeholder.com/640x420?text=Sin+imagen' }}" alt="{{ $artwork->title }}" class="w-100" style="height: 210px; object-fit: cover;">
                                </a>
                                <div class="p-3">
                                    <h3 class="h5 fw-bold mb-1">{{ $artwork->title }}</h3>
                                    @if($artwork->category)
                                        <span class="banter-badge" style="background: rgba(125,26,122,0.12); color: #7d1a7a;">{{ $artwork->category->name }}</span>
                                    @endif
                                    <div class="mt-2 small d-flex gap-3">
                                        <span><i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }}</span>
                                        <span><i class="fas fa-comment text-primary me-1"></i>{{ $artwork->comments_count }}</span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="banter-card p-4 text-center">Aun no tienes obras publicadas.</div>
                        @endforelse
                    </div>
                </div>

                @if(count($artworks) > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                        @foreach($artworks as $artwork)
                            <div class="col">
                                <article class="banter-card h-100 overflow-hidden">
                                    <img src="{{ $artwork->image_path ? asset('storage/' . $artwork->image_path) : 'https://via.placeholder.com/640x420?text=Sin+imagen' }}" alt="{{ $artwork->title }}" class="w-100" style="height: 220px; object-fit: cover;">
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h3 class="h5 fw-bold mb-1">{{ $artwork->title }}</h3>
                                            <span class="banter-badge {{ $artwork->is_public ? 'bg-success' : 'bg-secondary' }}">{{ $artwork->is_public ? 'Publica' : 'Privada' }}</span>
                                        </div>
                                        <p class="text-muted small mb-2">{{ Str::limit($artwork->description, 85) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small"><i class="fas fa-heart text-danger me-1"></i>{{ $artwork->likes_count }} <i class="fas fa-comment text-primary ms-2 me-1"></i>{{ $artwork->comments_count }}</span>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('artworks.show', $artwork->id) }}" class="banter-btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('artworks.edit', $artwork->id) }}" class="banter-btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="banter-btn-outline btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $artwork->id }}"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="d-none" data-banter-tab-panel="info">
                <div class="banter-card p-4">
                    <h2 class="h4 fw-bold mb-3">Informacion del artista</h2>
                    <p class="mb-3">{{ $artist->bio ?: 'Sin biografia registrada.' }}</p>
                    <ul class="mb-0">
                        <li class="mb-2"><strong>Website:</strong> {{ $websiteUrl ?: 'No configurado' }}</li>
                        <li class="mb-2"><strong>Instagram:</strong> {{ $instagram ?: 'No configurado' }}</li>
                        <li class="mb-2"><strong>Twitter:</strong> {{ $twitter ?: 'No configurado' }}</li>
                        <li><strong>Facebook:</strong> {{ $facebook ?: 'No configurado' }}</li>
                    </ul>
                </div>
            </div>

            <div class="d-none" data-banter-tab-panel="actividad">
                <div class="row g-4">
                    <div class="col-md-3"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Obras</p><h3 class="mb-0">{{ $artworks->count() }}</h3></div></div>
                    <div class="col-md-3"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Likes recibidos</p><h3 class="mb-0">{{ $totalLikes }}</h3></div></div>
                    <div class="col-md-3"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Comentarios</p><h3 class="mb-0">{{ $totalComments }}</h3></div></div>
                    <div class="col-md-3"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Seguidores</p><h3 class="mb-0">{{ $artist->followers()->count() }}</h3></div></div>
                    <div class="col-md-6"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Obras publicas</p><h3 class="mb-0">{{ $totalPublic }}</h3></div></div>
                    <div class="col-md-6"><div class="banter-card p-4 h-100"><p class="text-muted mb-1">Obras privadas</p><h3 class="mb-0">{{ $totalPrivate }}</h3></div></div>
                </div>
            </div>
        </section>
    </div>

    @foreach($artworks as $artwork)
        <div class="modal fade" id="deleteModal-{{ $artwork->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $artwork->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $artwork->id }}">Confirmar eliminacion</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Deseas eliminar "{{ $artwork->title }}"?</strong></p>
                        <ul>
                            <li>Comentarios: {{ $artwork->comments_count }}</li>
                            <li>Me gusta: {{ $artwork->likes_count }}</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('artworks.destroy', $artwork->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
