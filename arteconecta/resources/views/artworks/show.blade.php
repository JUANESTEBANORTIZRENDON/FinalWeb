<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <!-- Botón volver a galería -->
                <a href="{{ route('artworks.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> Volver a galería
                </a>
                
                <!-- Tarjeta de la obra -->
                <div class="card shadow-sm">
                    <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                         class="card-img-top" alt="{{ $artwork->title }}"
                         style="max-height: 500px; object-fit: contain;">
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="card-title">{{ $artwork->title }}</h1>
                            
                            @if($artwork->category)
                                <span class="badge bg-info">{{ $artwork->category->name }}</span>
                            @endif
                        </div>
                        
                        <p class="card-text">
                            <small class="text-muted">
                                Por: <a href="{{ route('artist.profile.public', $artwork->artist_id) }}" 
                                      class="text-decoration-none">{{ $artwork->artist->name }}</a>
                            </small>
                        </p>
                        
                        @if($artwork->creation_date)
                            <p class="card-text">
                                <small class="text-muted">Fecha de creación: {{ $artwork->creation_date->format('d/m/Y') }}</small>
                            </p>
                        @endif
                        
                        @if($artwork->technique)
                            <p class="card-text"><strong>Técnica:</strong> {{ $artwork->technique }}</p>
                        @endif
                        
                        @if($artwork->dimensions)
                            <p class="card-text"><strong>Dimensiones:</strong> {{ $artwork->dimensions }}</p>
                        @endif
                        
                        @if($artwork->description)
                            <div class="mt-3">
                                <h5>Descripción</h5>
                                <p class="card-text">{{ $artwork->description }}</p>
                            </div>
                        @endif
                        
                        <!-- Sección de interacción (likes) -->
                        <div class="mt-4 d-flex align-items-center">
                            @auth
                                @if(auth()->id() !== $artwork->artist_id)
                                    <button class="btn {{ $userHasLiked ? 'btn-danger' : 'btn-outline-danger' }} me-2 btn-like" 
                                            data-artwork-id="{{ $artwork->id }}">
                                        <i class="bi bi-heart{{ $userHasLiked ? '-fill' : '' }}"></i> 
                                        <span class="likes-count">{{ $artwork->likes->count() }}</span>
                                    </button>
                                @else
                                    <button class="btn btn-outline-danger me-2" disabled>
                                        <i class="bi bi-heart"></i> 
                                        <span class="likes-count">{{ $artwork->likes->count() }}</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-danger me-2">
                                    <i class="bi bi-heart"></i> 
                                    <span class="likes-count">{{ $artwork->likes->count() }}</span>
                                </a>
                            @endauth
                            
                            <span class="ms-3">
                                <i class="bi bi-chat-fill text-primary"></i> 
                                {{ $artwork->comments->count() }} comentarios
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de comentarios -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Comentarios</h4>
                    </div>
                    <div class="card-body">
                        @if($artwork->comments->count() > 0)
                            <div class="comments-list">
                                @foreach($artwork->comments->sortByDesc('created_at') as $comment)
                                    <div class="comment mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted ms-2">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        <p class="mb-1">{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted">No hay comentarios todavía. ¡Sé el primero en comentar!</p>
                        @endif
                        
                        <!-- Formulario para añadir comentario -->
                        @auth
                            <form method="POST" action="{{ route('artworks.comments.store', $artwork->id) }}" class="mt-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="content" class="form-label">Añadir un comentario</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="3" 
                                              maxlength="500" required>{{ old('content') }}</textarea>
                                    <div class="form-text">Máximo 500 caracteres</div>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">Publicar comentario</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info mt-3">
                                <p class="mb-0 text-center">
                                    <a href="{{ route('login') }}" class="alert-link">Inicia sesión</a> 
                                    para dejar un comentario.
                                </p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Información del artista -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Sobre el artista</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($artwork->artist->avatar_path)
                                <img src="{{ asset('storage/' . $artwork->artist->avatar_path) }}" 
                                     class="rounded-circle me-3" 
                                     alt="{{ $artwork->artist->name }}" 
                                     width="64" height="64"
                                     style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                     style="width: 64px; height: 64px;">
                                    {{ strtoupper(substr($artwork->artist->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $artwork->artist->name }}</h5>
                                <span class="badge bg-primary">Artista</span>
                            </div>
                        </div>
                        
                        @if($artwork->artist->bio)
                            <p>{{ Str::limit($artwork->artist->bio, 150) }}</p>
                        @else
                            <p class="text-muted">Este artista aún no ha añadido una biografía.</p>
                        @endif
                        
                        @auth
                            @if(auth()->id() !== $artwork->artist_id)
                                <!-- Botón de seguir artista -->
                                @php
                                    $isFollowing = Auth::user()->followedArtists()->where('artist_id', $artwork->artist_id)->exists();
                                @endphp
                                <button class="btn {{ $isFollowing ? 'btn-primary' : 'btn-outline-primary' }} w-100 mb-2 btn-follow" 
                                        data-artist-id="{{ $artwork->artist_id }}">
                                    <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }} me-1"></i> 
                                    <span class="follow-text">{{ $isFollowing ? 'Siguiendo' : 'Seguir artista' }}</span>
                                </button>
                            @endif
                        @endauth
                        
                        <a href="{{ route('artist.profile.public', $artwork->artist_id) }}" 
                           class="btn btn-outline-primary w-100">
                            Ver perfil completo
                        </a>
                    </div>
                </div>
                
                <!-- Más obras de este artista -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Más obras de este artista</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $otherArtworks = $artwork->artist->artworks()
                                ->where('id', '!=', $artwork->id)
                                ->where('is_public', true)
                                ->latest()
                                ->take(4)
                                ->get();
                        @endphp
                        
                        @if($otherArtworks->count() > 0)
                            <div class="row row-cols-2 g-2">
                                @foreach($otherArtworks as $otherArtwork)
                                    <div class="col">
                                        <a href="{{ route('artworks.show', $otherArtwork->id) }}" class="text-decoration-none">
                                            <div class="card h-100">
                                                <img src="{{ asset('storage/' . $otherArtwork->image_path) }}" 
                                                     class="card-img-top" alt="{{ $otherArtwork->title }}"
                                                     style="height: 100px; object-fit: cover;">
                                                <div class="card-body p-2">
                                                    <p class="card-text small text-truncate">{{ $otherArtwork->title }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 text-center">
                                <a href="{{ route('artist.profile.public', $artwork->artist_id) }}" class="text-decoration-none">
                                    Ver todas las obras
                                </a>
                            </div>
                        @else
                            <p class="text-muted text-center">No hay más obras públicas de este artista.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar los likes mediante AJAX
            const likeButtons = document.querySelectorAll('.btn-like');
            
            likeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const artworkId = this.getAttribute('data-artwork-id');
                    const likesCountEl = this.querySelector('.likes-count');
                    const iconEl = this.querySelector('i');
                    
                    fetch(`/artworks/${artworkId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar el conteo de likes
                        likesCountEl.textContent = data.likesCount;
                        
                        // Actualizar el estado del botón
                        if (data.liked) {
                            button.classList.remove('btn-outline-danger');
                            button.classList.add('btn-danger');
                            iconEl.classList.remove('bi-heart');
                            iconEl.classList.add('bi-heart-fill');
                        } else {
                            button.classList.remove('btn-danger');
                            button.classList.add('btn-outline-danger');
                            iconEl.classList.remove('bi-heart-fill');
                            iconEl.classList.add('bi-heart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
            
            // Manejar los botones de seguir mediante AJAX
            const followButtons = document.querySelectorAll('.btn-follow');
            
            followButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const artistId = this.getAttribute('data-artist-id');
                    const followTextEl = this.querySelector('.follow-text');
                    const iconEl = this.querySelector('i');
                    
                    // Desactivar el botón temporalmente para evitar clics múltiples
                    this.disabled = true;
                    
                    // Cambio optimista de la UI antes de la respuesta del servidor
                    const isCurrentlyFollowing = button.classList.contains('btn-primary');
                    
                    if (isCurrentlyFollowing) {
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-outline-primary');
                        iconEl.classList.remove('fa-user-check');
                        iconEl.classList.add('fa-user-plus');
                        followTextEl.textContent = 'Seguir artista';
                    } else {
                        button.classList.remove('btn-outline-primary');
                        button.classList.add('btn-primary');
                        iconEl.classList.remove('fa-user-plus');
                        iconEl.classList.add('fa-user-check');
                        followTextEl.textContent = 'Siguiendo';
                    }
                    
                    fetch(`/artists/${artistId}/follow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Reactivar el botón
                        button.disabled = false;
                        
                        // Actualizar la UI según la respuesta real del servidor
                        // (por si la respuesta difiere de nuestro cambio optimista)
                        if (data.following) {
                            button.classList.remove('btn-outline-primary');
                            button.classList.add('btn-primary');
                            iconEl.classList.remove('fa-user-plus');
                            iconEl.classList.add('fa-user-check');
                            followTextEl.textContent = 'Siguiendo';
                        } else {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-primary');
                            iconEl.classList.remove('fa-user-check');
                            iconEl.classList.add('fa-user-plus');
                            followTextEl.textContent = 'Seguir artista';
                        }
                        
                        // Actualizar contador de seguidores si existe en la página
                        const followersCountEl = document.querySelector('.followers-count');
                        if (followersCountEl && data.followersCount !== undefined) {
                            followersCountEl.textContent = data.followersCount;
                        }
                        
                        console.log('Respuesta del servidor:', data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        
                        // Revertir los cambios visuales en caso de error
                        if (isCurrentlyFollowing) {
                            button.classList.remove('btn-outline-primary');
                            button.classList.add('btn-primary');
                            iconEl.classList.remove('fa-user-plus');
                            iconEl.classList.add('fa-user-check');
                            followTextEl.textContent = 'Siguiendo';
                        } else {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-primary');
                            iconEl.classList.remove('fa-user-check');
                            iconEl.classList.add('fa-user-plus');
                            followTextEl.textContent = 'Seguir artista';
                        }
                        
                        alert('Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.');
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
