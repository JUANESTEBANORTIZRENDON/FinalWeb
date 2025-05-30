<x-app-layout>
    <div class="container py-4">
        <h1 class="h2 fw-bold mb-4">Galería de Obras</h1>
        <p class="lead mb-4">Explora y conecta con el arte independiente</p>
        
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($artworks->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($artworks as $artwork)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $artwork->image_path) }}" 
                                     class="card-img-top" alt="{{ $artwork->title }}"
                                     style="height: 200px; object-fit: cover;">
                                
                                <!-- Badge de categoría -->
                                @if($artwork->category)
                                    <span class="position-absolute top-0 end-0 badge bg-info m-2">
                                        {{ $artwork->category->name }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $artwork->title }}</h5>
                                
                                <!-- Enlace al perfil del artista -->
                                <p class="card-text">
                                    <small class="text-muted">
                                        Por: <a href="{{ route('artist.profile.public', $artwork->artist_id) }}" class="text-decoration-none">
                                            {{ $artwork->artist->name }}
                                        </a>
                                    </small>
                                </p>
                                
                                <!-- Previsualización de descripción si existe -->
                                @if($artwork->description)
                                    <p class="card-text">{{ Str::limit($artwork->description, 100) }}</p>
                                @endif
                                
                                <!-- Estadísticas y acciones -->
                                <div class="social-actions mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @auth
                                                <!-- Botón de like interactivo -->
                                                <button class="btn btn-sm like-button {{ $artwork->likes->where('user_id', Auth::id())->count() ? 'liked' : '' }}" 
                                                        onclick="toggleLike('{{ $artwork->id }}')" id="like-btn-{{ $artwork->id }}">
                                                    <i class="fas fa-heart"></i> 
                                                    <span id="likes-count-{{ $artwork->id }}">{{ $artwork->likes->count() }}</span>
                                                </button>

                                                <!-- Botón para comentar -->                                                
                                                <a href="{{ route('artworks.show', $artwork->id) }}#comments" class="btn btn-sm comment-button">
                                                    <i class="fas fa-comment"></i> 
                                                    <span>{{ $artwork->comments->count() }}</span>
                                                </a>
                                            @else
                                                <!-- Versión para usuarios no autenticados -->
                                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="fas fa-heart"></i> {{ $artwork->likes->count() }}
                                                </a>
                                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-comment"></i> {{ $artwork->comments->count() }}
                                                </a>
                                            @endauth
                                        </div>

                                        <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-primary">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación personalizada -->
            <div class="mt-4 d-flex justify-content-center">
                <div class="pagination-modern">
                    @if ($artworks->hasPages())
                        <div class="pagination-container">
                            <!-- Botón Anterior -->
                            @if ($artworks->onFirstPage())
                                <span class="pagination-button disabled">Anterior</span>
                            @else
                                <a href="{{ $artworks->previousPageUrl() }}" class="pagination-button">
                                    Anterior
                                </a>
                            @endif

                            <!-- Números de páginas -->
                            <div class="pagination-numbers">
                                @for ($i = 1; $i <= $artworks->lastPage(); $i++)
                                    @if ($i == $artworks->currentPage())
                                        <span class="pagination-number active">{{ $i }}</span>
                                    @else
                                        <a href="{{ $artworks->url($i) }}" class="pagination-number">{{ $i }}</a>
                                    @endif
                                @endfor
                            </div>

                            <!-- Botón Siguiente -->
                            @if ($artworks->hasMorePages())
                                <a href="{{ $artworks->nextPageUrl() }}" class="pagination-button">
                                    Siguiente
                                </a>
                            @else
                                <span class="pagination-button disabled">Siguiente</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <p class="mb-0 text-center">No hay obras públicas disponibles en este momento.</p>
            </div>
        @endif
    </div>

    <!-- Estilos para los botones de acción social -->
    <style>
        .social-actions .btn {
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .like-button {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .like-button.liked {
            background-color: #f06292;
            color: white;
            border-color: #f06292;
        }
        
        .like-button:hover {
            background-color: #f48fb1;
            color: white;
        }
        
        .comment-button {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .comment-button:hover {
            background-color: #90caf9;
            color: white;
            border-color: #90caf9;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Estilos para los botones de paginación modernos */
        .pagination-modern {
            margin: 2rem 0;
            font-family: 'Poppins', sans-serif;
        }
        
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .pagination-button {
            display: inline-flex;
            padding: 8px 16px;
            background-color: #7b1fa2;
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(123, 31, 162, 0.3);
            min-width: 100px;
            justify-content: center;
        }
        
        .pagination-button:hover {
            background-color: #9c27b0;
            box-shadow: 0 5px 15px rgba(123, 31, 162, 0.4);
            transform: translateY(-2px);
        }
        
        .pagination-button.disabled {
            background-color: #e1bee7;
            color: #9e9e9e;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        
        .pagination-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #f3e5f5;
            color: #7b1fa2;
            transition: all 0.2s ease;
            text-decoration: none;
            font-weight: 500;
        }
        
        .pagination-number:hover {
            background-color: #ce93d8;
            color: white;
            transform: scale(1.1);
        }
        
        .pagination-number.active {
            background-color: #7b1fa2;
            color: white;
            box-shadow: 0 2px 8px rgba(123, 31, 162, 0.5);
        }
    </style>

    <!-- Script para manejar los likes de forma asíncrona con respuesta inmediata -->
    <script>
        function toggleLike(artworkId) {
            // Obtener elementos del DOM
            const likeButton = document.getElementById(`like-btn-${artworkId}`);
            const likesCountElement = document.getElementById(`likes-count-${artworkId}`);
            const currentLikes = parseInt(likesCountElement.textContent, 10);
            
            // Aplicar cambio inmediato en la interfaz
            if (likeButton.classList.contains('liked')) {
                // Si ya tiene like, quitar el like inmediatamente
                likeButton.classList.remove('liked');
                likesCountElement.textContent = Math.max(0, currentLikes - 1);
            } else {
                // Si no tiene like, agregar like inmediatamente
                likeButton.classList.add('liked');
                likesCountElement.textContent = currentLikes + 1;
            }
            
            // Deshabilitar el botón temporalmente para evitar clics múltiples
            likeButton.disabled = true;
            
            // Realizar la petición AJAX en segundo plano
            fetch(`/artworks/${artworkId}/like`, {
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
                // Actualizar con los datos reales del servidor (por si acaso)
                likesCountElement.textContent = data.likesCount;
                
                if (data.liked) {
                    likeButton.classList.add('liked');
                } else {
                    likeButton.classList.remove('liked');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revertir el cambio visual en caso de error
                if (likeButton.classList.contains('liked')) {
                    likeButton.classList.remove('liked');
                    likesCountElement.textContent = Math.max(0, parseInt(likesCountElement.textContent, 10) - 1);
                } else {
                    likeButton.classList.add('liked');
                    likesCountElement.textContent = parseInt(likesCountElement.textContent, 10) + 1;
                }
            })
            .finally(() => {
                // Volver a habilitar el botón
                likeButton.disabled = false;
            });
        }
    </script>
</x-app-layout>
