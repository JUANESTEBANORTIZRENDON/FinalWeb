<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 mb-0 fw-bold">Mis obras</h1>
                            <a href="{{ route('artworks.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Subir nueva obra
                            </a>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(count($artworks) > 0)
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                @foreach($artworks as $artwork)
                                    <div class="col">
                                        <div class="card h-100 shadow-sm artwork-card">
                                            <div class="artwork-image-container">
                                                <img src="{{ Storage::url($artwork->image_path) }}" 
                                                     alt="{{ $artwork->title }}" 
                                                     class="card-img-top artwork-image"
                                                     onerror="this.src='https://via.placeholder.com/300x200?text=Imagen+no+disponible'">
                                                
                                                <div class="artwork-status">
                                                    @if($artwork->is_public)
                                                        <span class="badge bg-success">Pública</span>
                                                    @else
                                                        <span class="badge bg-secondary">Privada</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="card-body">
                                                <h3 class="h5 card-title fw-bold">{{ $artwork->title }}</h3>
                                                
                                                @if($artwork->category)
                                                    <p class="text-primary small mb-2">{{ $artwork->category->name }}</p>
                                                @endif
                                                
                                                <p class="card-text small text-muted text-truncate">
                                                    {{ $artwork->description ?: 'Sin descripción' }}
                                                </p>
                                                
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div class="btn-group w-100">
                                                        <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i> Ver
                                                        </a>
                                                        <a href="{{ route('artworks.edit', $artwork->id) }}" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $artwork->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card-footer bg-white">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">{{ $artwork->created_at->format('d/m/Y') }}</small>
                                                    <div>
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
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-images fa-4x text-muted"></i>
                                </div>
                                <h2 class="h4 text-muted">Aún no has subido ninguna obra</h2>
                                <p class="text-muted mb-4">Comparte tu arte con la comunidad de ArteConecta</p>
                                <a href="{{ route('artworks.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Subir mi primera obra
                                </a>
                            </div>
                        @endif
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

    <style>
        .artwork-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .artwork-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .artwork-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .artwork-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .artwork-status {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }
    </style>
</x-app-layout>
