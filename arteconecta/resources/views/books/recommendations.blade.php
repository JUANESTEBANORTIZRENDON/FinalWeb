<x-app-layout>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h3 fw-bold mb-4 text-center text-md-start">
                            <i class="fas fa-book me-2 text-primary"></i> Biblioteca de Arte
                        </h1>
                        
                        <!-- Buscador de libros -->
                        <form action="{{ route('books.recommendations') }}" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Buscar libros de arte..." 
                                    value="{{ $searchTerm }}" aria-label="Buscar libros">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Categorías populares -->
                        <div class="mb-4">
                            <h5 class="mb-3">Categorías populares:</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($categories as $category)
                                    <a href="{{ route('books.recommendations', ['q' => $category]) }}" 
                                        class="btn btn-sm btn-outline-secondary">
                                        {{ $category }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-4">
                            Resultados para: "{{ $searchTerm }}"
                        </h2>
                        
                        @if (empty($books))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No se encontraron libros para esta búsqueda. Intenta con otros términos.
                            </div>
                        @else
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                                @foreach ($books as $book)
                                    <div class="col">
                                        <div class="card h-100 shadow-sm">
                                            <!-- Imagen del libro -->
                                            <div class="text-center pt-3">
                                                <img src="{{ $book['thumbnail'] }}" alt="{{ $book['title'] }}" 
                                                    class="img-fluid book-cover" style="height: 180px; width: auto;">
                                            </div>
                                            
                                            <div class="card-body">
                                                <!-- Título y autores -->
                                                <h5 class="card-title fw-bold" style="font-size: 1rem;">
                                                    {{ Str::limit($book['title'], 50) }}
                                                </h5>
                                                <p class="card-text text-muted" style="font-size: 0.9rem;">
                                                    {{ implode(', ', array_slice($book['authors'], 0, 2)) }}
                                                    @if (count($book['authors']) > 2)
                                                        <span>y otros</span>
                                                    @endif
                                                </p>
                                                
                                                <!-- Descripción corta -->
                                                <p class="card-text" style="font-size: 0.85rem;">
                                                    {!! Str::limit($book['description'], 150) !!}
                                                </p>
                                                
                                                <!-- Detalles adicionales -->
                                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.8rem;">
                                                    <span class="text-muted">{{ $book['publishedDate'] }}</span>
                                                    @if ($book['pageCount'] > 0)
                                                        <span class="badge bg-light text-dark">{{ $book['pageCount'] }} págs</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="card-footer bg-white">
                                                <div class="d-grid gap-2">
                                                    <a href="{{ $book['infoLink'] }}" target="_blank" 
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt me-1"></i> Ver más información
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .book-cover {
            transition: transform 0.3s ease;
        }
        .card:hover .book-cover {
            transform: scale(1.05);
        }
    </style>
</x-app-layout>
