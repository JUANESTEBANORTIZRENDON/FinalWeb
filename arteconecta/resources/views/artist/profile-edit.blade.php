<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-4">Editar perfil de artista</h2>
                        
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('artist.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="row g-4">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <!-- Nombre -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre completo</label>
                                        <input id="name" name="name" type="text" value="{{ old('name', $artist->name) }}" required 
                                            class="form-control @error('name') is-invalid @enderror">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Bio -->
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Biografía</label>
                                        <textarea id="bio" name="bio" rows="6" 
                                            class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $artist->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Describe tu trayectoria, estilo artístico y motivaciones. Esta información aparecerá en tu perfil público.</small>
                                    </div>
                                    
                                    <!-- Website URL -->
                                    <div class="mb-3">
                                        <label for="website_url" class="form-label">Sitio web (opcional)</label>
                                        <input id="website_url" name="website_url" type="url" value="{{ old('website_url', $artist->website_url) }}" 
                                            placeholder="https://ejemplo.com" 
                                            class="form-control @error('website_url') is-invalid @enderror">
                                        @error('website_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <!-- Avatar -->
                                    <div class="mb-4">
                                        <label class="form-label">Foto de perfil</label>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($artist->avatar_path)
                                                    <img src="{{ asset('storage/' . $artist->avatar_path) }}" alt="{{ $artist->name }}" 
                                                        class="rounded-circle shadow" style="width: 80px; height: 80px; object-fit: cover;"
                                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text={{ substr($artist->name, 0, 1) }}'; this.style.backgroundColor='#f3e8ff';">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                        style="width: 80px; height: 80px; background-color: #f3e8ff; color: #6b21a8;">
                                                        <span style="font-size: 2rem;">{{ substr($artist->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" id="avatar" name="avatar" accept="image/*" 
                                                    class="form-control @error('avatar') is-invalid @enderror">
                                                @error('avatar')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">JPG, PNG o GIF. Máximo 1MB.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Redes sociales -->
                                    <div>
                                        <label class="form-label fw-medium mb-3">Redes sociales (opcional)</label>
                                        
                                        <!-- Instagram -->
                                        <div class="mb-3">
                                            <label for="social_media_instagram" class="d-flex align-items-center mb-2">
                                                <i class="fab fa-instagram text-danger me-2"></i>
                                                Instagram
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">instagram.com/</span>
                                                <input id="social_media_instagram" name="social_media[instagram]" type="text" 
                                                    value="{{ old('social_media.instagram', $artist->social_media['instagram'] ?? '') }}" 
                                                    class="form-control">
                                            </div>
                                        </div>
                                        
                                        <!-- Twitter -->
                                        <div class="mb-3">
                                            <label for="social_media_twitter" class="d-flex align-items-center mb-2">
                                                <i class="fab fa-twitter text-info me-2"></i>
                                                Twitter
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">twitter.com/</span>
                                                <input id="social_media_twitter" name="social_media[twitter]" type="text" 
                                                    value="{{ old('social_media.twitter', $artist->social_media['twitter'] ?? '') }}" 
                                                    class="form-control">
                                            </div>
                                        </div>
                                        
                                        <!-- Facebook -->
                                        <div class="mb-3">
                                            <label for="social_media_facebook" class="d-flex align-items-center mb-2">
                                                <i class="fab fa-facebook text-primary me-2"></i>
                                                Facebook
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">facebook.com/</span>
                                                <input id="social_media_facebook" name="social_media[facebook]" type="text" 
                                                    value="{{ old('social_media.facebook', $artist->social_media['facebook'] ?? '') }}" 
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-end">
                                <a href="{{ route('artist.profile') }}" class="btn btn-light me-2">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
