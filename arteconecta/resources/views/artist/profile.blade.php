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
                                        {{ ucfirst($artist->user_type) }}
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
                                        <div class="h3 fw-bold mb-0">{{ count($artworks) }}</div>
                                        <div class="text-muted">Obras</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="h3 fw-bold mb-0">{{ $artist->followers()->count() }}</div>
                                        <div class="text-muted">Seguidores</div>
                                    </div>
                                </div>
                                
                                <!-- Botón de editar perfil -->
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                                    <a href="{{ route('artist.profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i> Editar perfil
                                    </a>
                                    <a href="{{ route('artist.publications.report') }}" class="btn btn-outline-secondary" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i> Reporte PDF
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Biografía y obras -->
                            <div class="col-md-8">
                                @if($artist->bio)
                                    <div class="mb-4">
                                        <h2 class="h5 fw-bold mb-3">Biografía</h2>
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <p>{{ $artist->bio }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Galería de obras -->
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h2 class="h5 fw-bold mb-0">Mis obras</h2>
                                        <a href="{{ route('artworks.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> Subir nueva obra
                                        </a>
                                    </div>
                                    
                                    @if(count($artworks) > 0)
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
                                                                <div class="btn-group" role="group">
                                                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-eye"></i> Ver
                                                                    </a>
                                                                    <a href="{{ route('artworks.edit', $artwork->id) }}" class="btn btn-sm btn-outline-secondary">
                                                                        <i class="fas fa-edit"></i> Editar
                                                                    </a>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $artwork->id }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
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
                                        <div class="card text-center p-4 bg-light">
                                            <div class="card-body">
                                                <i class="fas fa-images fa-3x text-secondary mb-3"></i>
                                                <p class="text-muted">Aún no has subido ninguna obra de arte</p>
                                                <a href="{{ route('artworks.create') }}" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus me-2"></i> Subir nueva obra
                                                </a>
                                            </div>
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
    
    <!-- Modales de confirmación para eliminar obras -->
    @foreach($artworks as $artwork)
        <div class="modal fade" id="deleteModal-{{ $artwork->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $artwork->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel-{{ $artwork->id }}">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>¿Estás seguro que deseas eliminar la obra "{{ $artwork->title }}"?</strong></p>
                        <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Esta acción no se puede deshacer.</p>
                        <p>Se eliminarán permanentemente:</p>
                        <ul>
                            <li>La imagen de la obra</li>
                            <li>Todos los comentarios ({{ $artwork->comments()->count() }})</li>
                            <li>Todos los "me gusta" ({{ $artwork->likes()->count() }})</li>
                            <li>Toda la información asociada a esta obra</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('artworks.destroy', $artwork->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar definitivamente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
