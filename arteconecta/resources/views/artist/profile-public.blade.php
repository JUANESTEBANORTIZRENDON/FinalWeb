<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <!-- Información del artista -->
                        <div class="row g-4">
                            <!-- Avatar y datos básicos -->
                            <div class="col-md-4 text-center">
                                <div class="mb-4 mx-auto" style="width: 180px; height: 180px;">
                                    @if($artist->avatar_path)
                                        <img src="{{ asset('storage/' . $artist->avatar_path) }}" alt="{{ $artist->name }}" 
                                            class="rounded-circle shadow" style="width: 100%; height: 100%; object-fit: cover;" 
                                            onerror="this.onerror=null; this.src='https://via.placeholder.com/180?text={{ substr($artist->name, 0, 1) }}'; this.style.backgroundColor='#f3e8ff';">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                            style="width: 100%; height: 100%; background-color: #f3e8ff; color: #6b21a8;">
                                            <span style="font-size: 3rem;">{{ substr($artist->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <h1 class="h3 fw-bold mb-2">{{ $artist->name }}</h1>
                                
                                <!-- Tipo de usuario -->
                                <div class="mb-3">
                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="fas fa-user-circle me-1"></i>
                                        Artista
                                    </span>
                                </div>
                                
                                <!-- Enlaces a redes sociales -->
                                <div class="d-flex justify-content-center gap-3 mb-4">
                                    @if(isset($artist->social_media['instagram']) && $artist->social_media['instagram'])
                                        <a href="https://instagram.com/{{ $artist->social_media['instagram'] }}" target="_blank" class="text-danger">
                                            <i class="fab fa-instagram fa-lg"></i>
                                        </a>
                                    @endif
                                    
                                    @if(isset($artist->social_media['twitter']) && $artist->social_media['twitter'])
                                        <a href="https://twitter.com/{{ $artist->social_media['twitter'] }}" target="_blank" class="text-info">
                                            <i class="fab fa-twitter fa-lg"></i>
                                        </a>
                                    @endif
                                    
                                    @if(isset($artist->social_media['facebook']) && $artist->social_media['facebook'])
                                        <a href="https://facebook.com/{{ $artist->social_media['facebook'] }}" target="_blank" class="text-primary">
                                            <i class="fab fa-facebook fa-lg"></i>
                                        </a>
                                    @endif
                                    
                                    @if($artist->website_url)
                                        <a href="{{ $artist->website_url }}" target="_blank" class="text-secondary">
                                            <i class="fas fa-globe fa-lg"></i>
                                        </a>
                                    @endif
                                </div>
                                
                                <!-- Estadísticas del artista -->
                                <div class="d-flex justify-content-center gap-4 mb-3">
                                    <div class="text-center">
                                        <div class="h3 fw-bold mb-0">{{ $artworks->count() }}</div>
                                        <div class="text-muted">Obras</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="h3 fw-bold mb-0" id="followers-count">{{ $artist->followers()->count() }}</div>
                                        <div class="text-muted">Seguidores</div>
                                    </div>
                                </div>
                                
                                <!-- Botón para seguir o iniciar sesión -->
                                @auth
                                    @if(Auth::id() !== $artist->id)
                                        @php
                                            $isFollowing = $artist->followers()->where('follower_id', Auth::id())->exists();
                                        @endphp
                                        <button class="btn {{ $isFollowing ? 'btn-outline-primary' : 'btn-primary' }}" 
                                                id="follow-button"
                                                onclick="toggleFollow('{{ $artist->id }}')">
                                            <i class="fas {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }} me-1"></i>
                                            <span id="follow-text">{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-sign-in-alt me-1"></i> Inicia sesión para seguir
                                    </a>
                                @endauth
                            </div>
                            
                            <!-- Biografía y obras -->
                            <div class="col-md-8">
                                @if($artist->bio)
                                    <div class="mb-4">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light">
                                                <h2 class="h5 fw-bold mb-0">Biografía</h2>
                                            </div>
                                            <div class="card-body">
                                                <p>{{ $artist->bio }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Galería de obras públicas -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2 class="h5 fw-bold mb-0">Obras</h2>
                                    </div>
                                    
                                    @if($artworks->count() > 0)
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                                            @foreach($artworks as $artwork)
                                                <div class="col">
                                                    <div class="card h-100 shadow-sm">
                                                        <div style="height: 200px; overflow: hidden;">
                                                            @if($artwork->image_path)
                                                                <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                                                                    alt="{{ $artwork->title }}" 
                                                                    class="w-100 h-100" style="object-fit: cover;">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="card-body">
                                                            <h3 class="h6 fw-bold">
                                                                <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none text-dark">
                                                                    {{ $artwork->title }}
                                                                </a>
                                                            </h3>
                                                            @if($artwork->category)
                                                                <p class="text-primary small mb-2">{{ $artwork->category->name }}</p>
                                                            @endif
                                                            <p class="small text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                {{ $artwork->description }}
                                                            </p>
                                                            
                                                            <!-- Estadísticas y acciones -->
                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-outline-primary">
                                                                    Ver detalles
                                                                </a>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2" title="Me gusta">
                                                                        <i class="bi bi-heart-fill text-danger"></i> 
                                                                        {{ $artwork->likes()->count() }}
                                                                    </span>
                                                                    <span title="Comentarios">
                                                                        <i class="bi bi-chat-fill text-primary"></i> 
                                                                        {{ $artwork->comments()->count() }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <p class="mb-0 text-center">Este artista aún no ha publicado obras.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFollow(artistId) {
            // Obtener elementos del DOM
            const followButton = document.getElementById('follow-button');
            const followText = document.getElementById('follow-text');
            const followIcon = followButton.querySelector('i');
            const followersCountElement = document.getElementById('followers-count');
            const currentFollowers = parseInt(followersCountElement.textContent, 10);
            
            // Determinar estado actual
            const isCurrentlyFollowing = followButton.classList.contains('btn-outline-primary');
            
            // Actualizar UI inmediatamente (optimistic update)
            if (isCurrentlyFollowing) {
                // Si ya está siguiendo, quitar seguimiento
                followButton.classList.remove('btn-outline-primary');
                followButton.classList.add('btn-primary');
                followText.textContent = 'Seguir';
                followIcon.classList.remove('fa-user-check');
                followIcon.classList.add('fa-user-plus');
                followersCountElement.textContent = Math.max(0, currentFollowers - 1);
            } else {
                // Si no está siguiendo, agregar seguimiento
                followButton.classList.remove('btn-primary');
                followButton.classList.add('btn-outline-primary');
                followText.textContent = 'Siguiendo';
                followIcon.classList.remove('fa-user-plus');
                followIcon.classList.add('fa-user-check');
                followersCountElement.textContent = currentFollowers + 1;
            }
            
            // Deshabilitar el botón temporalmente para evitar clics múltiples
            followButton.disabled = true;
            
            // Realizar la petición AJAX en segundo plano
            fetch(`/artists/${artistId}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                // Actualizar con el conteo real del servidor (por si acaso)
                followersCountElement.textContent = data.followersCount;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Revertir cambios en caso de error
                if (isCurrentlyFollowing) {
                    // Revertir a estado de siguiendo
                    followButton.classList.remove('btn-primary');
                    followButton.classList.add('btn-outline-primary');
                    followText.textContent = 'Siguiendo';
                    followIcon.classList.remove('fa-user-plus');
                    followIcon.classList.add('fa-user-check');
                    followersCountElement.textContent = currentFollowers;
                } else {
                    // Revertir a estado de no siguiendo
                    followButton.classList.remove('btn-outline-primary');
                    followButton.classList.add('btn-primary');
                    followText.textContent = 'Seguir';
                    followIcon.classList.remove('fa-user-check');
                    followIcon.classList.add('fa-user-plus');
                    followersCountElement.textContent = currentFollowers;
                }
            })
            .finally(() => {
                // Volver a habilitar el botón
                followButton.disabled = false;
            });
        }
    </script>
</x-app-layout>
