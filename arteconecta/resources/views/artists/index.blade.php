<x-app-layout>
    <div class="container py-5">
        <h1 class="h2 fw-bold mb-4">Artistas en ArteConecta</h1>
        <p class="lead mb-5">Descubre talentosos artistas y explora sus creaciones</p>

        @if(count($artists) > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($artists as $artist)
                    <div class="col">
                        <div class="card h-100 artist-card shadow-sm">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    @if($artist->avatar_path)
                                        <img src="{{ Storage::url($artist->avatar_path) }}" 
                                             alt="{{ $artist->name }}" 
                                             class="rounded-circle artist-avatar mx-auto"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text={{ substr($artist->name, 0, 1) }}'; this.style.backgroundColor='#f3e8ff';">
                                    @else
                                        <div class="avatar-placeholder mx-auto">
                                            <span>{{ substr($artist->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <h3 class="h5 card-title mb-2">{{ $artist->name }}</h3>
                                
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="fas fa-paint-brush me-1"></i> Artista
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <p class="mb-0">
                                        <i class="fas fa-images me-1 text-primary"></i>
                                        <strong>{{ $artist->artworks_count }}</strong> 
                                        {{ $artist->artworks_count == 1 ? 'obra' : 'obras' }}
                                    </p>
                                </div>
                                
                                @if(isset($artist->social_media) && !empty($artist->social_media))
                                    <div class="social-links d-flex justify-content-center gap-2 mb-3">
                                        @if(isset($artist->social_media['instagram']) && $artist->social_media['instagram'])
                                            <a href="https://instagram.com/{{ $artist->social_media['instagram'] }}" target="_blank" class="social-link instagram">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        @endif
                                        
                                        @if(isset($artist->social_media['twitter']) && $artist->social_media['twitter'])
                                            <a href="https://twitter.com/{{ $artist->social_media['twitter'] }}" target="_blank" class="social-link twitter">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        @endif
                                        
                                        @if(isset($artist->social_media['facebook']) && $artist->social_media['facebook'])
                                            <a href="https://facebook.com/{{ $artist->social_media['facebook'] }}" target="_blank" class="social-link facebook">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <a href="{{ route('artist.profile.public', $artist->id) }}" class="btn btn-primary mt-2">
                                    Ver perfil
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center p-5">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>No hay artistas registrados todavía</h4>
                <p>¡Sé el primero en unirte como artista a nuestra comunidad!</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary mt-3">Registrarse como artista</a>
                @endguest
            </div>
        @endif
    </div>

    <style>
        .artist-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .artist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .artist-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #f3e8ff;
        }
        
        .avatar-placeholder {
            width: 150px;
            height: 150px;
            background-color: #f3e8ff;
            color: #6b21a8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
        }
        
        .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f3e8ff;
            color: #6b21a8;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }
        
        .social-link.instagram:hover {
            background-color: #e1306c;
            color: white;
        }
        
        .social-link.twitter:hover {
            background-color: #1da1f2;
            color: white;
        }
        
        .social-link.facebook:hover {
            background-color: #1877f2;
            color: white;
        }
    </style>
</x-app-layout>
