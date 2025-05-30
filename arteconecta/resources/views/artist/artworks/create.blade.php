<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">Subir nueva obra</h2>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('artworks.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Título -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Título *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoría -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Categoría *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Imagen -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Imagen *</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/jpeg,image/png,image/jpg" required>
                                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 12MB</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Técnica -->
                            <div class="mb-3">
                                <label for="technique" class="form-label">Técnica</label>
                                <input type="text" class="form-control @error('technique') is-invalid @enderror" 
                                       id="technique" name="technique" value="{{ old('technique') }}">
                                @error('technique')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dimensiones -->
                            <div class="mb-3">
                                <label for="dimensions" class="form-label">Dimensiones</label>
                                <input type="text" class="form-control @error('dimensions') is-invalid @enderror" 
                                       id="dimensions" name="dimensions" value="{{ old('dimensions') }}" 
                                       placeholder="Ej: 30x40 cm">
                                @error('dimensions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de creación -->
                            <div class="mb-3">
                                <label for="creation_date" class="form-label">Fecha de creación</label>
                                <input type="date" class="form-control @error('creation_date') is-invalid @enderror" 
                                       id="creation_date" name="creation_date" value="{{ old('creation_date') }}">
                                @error('creation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Visibilidad -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" 
                                       {{ old('is_public', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">Hacer pública esta obra</label>
                                <div class="form-text">Las obras públicas serán visibles para todos los usuarios.</div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('artist.profile') }}" class="btn btn-outline-danger me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar obra</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
