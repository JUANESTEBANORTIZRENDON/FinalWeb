<x-app-layout>
    @php
        $nameParts = preg_split('/\s+/', trim($artist->name ?? ''));
        $initials = collect($nameParts)
            ->filter()
            ->map(function ($part) {
                return mb_strtoupper(mb_substr($part, 0, 1));
            })
            ->take(2)
            ->implode('');

        $instagram = $artist->social_media['instagram'] ?? null;
        $twitter = $artist->social_media['twitter'] ?? null;
        $facebook = $artist->social_media['facebook'] ?? null;

        $instagramUrl = $instagram
            ? (str_starts_with($instagram, 'http') ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@'))
            : null;
        $twitterUrl = $twitter
            ? (str_starts_with($twitter, 'http') ? $twitter : 'https://twitter.com/' . ltrim($twitter, '@'))
            : null;
        $facebookUrl = $facebook
            ? (str_starts_with($facebook, 'http') ? $facebook : 'https://facebook.com/' . ltrim($facebook, '@'))
            : null;

        $websiteUrl = $artist->website_url ?? null;
        if ($websiteUrl && !str_starts_with($websiteUrl, 'http')) {
            $websiteUrl = 'https://' . $websiteUrl;
        }
    @endphp

    <div class="container py-5 artist-profile">
        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm profile-header mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 col-lg-3 text-center">
                        <div class="profile-avatar mx-auto">
                            @if($artist->avatar_path)
                                <img
                                    src="{{ asset('storage/' . $artist->avatar_path) }}"
                                    alt="{{ $artist->name }}"
                                    class="profile-avatar-img"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/220?text={{ $initials ?: 'A' }}';"
                                >
                            @else
                                <div class="profile-avatar-placeholder">
                                    <span>{{ $initials ?: 'A' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-9">
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                            <div>
                                <h1 class="h2 fw-bold mb-1">{{ $artist->name }}</h1>
                                <div class="text-muted fw-semibold mb-2">Artista</div>
                                @if($artist->bio)
                                    <p class="text-muted mb-0 profile-bio-line">
                                        {{ $artist->bio }}
                                    </p>
                                @endif
                            </div>

                            <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-lg-end">
                                <a href="{{ route('artist.profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Editar perfil
                                </a>
                                <a href="{{ route('artist.publications.report') }}" class="btn btn-outline-secondary" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>Reporte PDF
                                </a>
                                <a href="{{ route('artworks.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus me-2"></i>Subir obra
                                </a>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ count($artworks) }}</div>
                                <div class="profile-stat-label">Obras</div>
                            </div>
                            <div class="profile-stat">
                                <div class="profile-stat-value">{{ $artist->followers()->count() }}</div>
                                <div class="profile-stat-label">Seguidores</div>
                            </div>
                            <div class="profile-socials ms-lg-auto">
                                @if($instagramUrl)
                                    <a href="{{ $instagramUrl }}" target="_blank" class="social-btn social-instagram" aria-label="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if($twitterUrl)
                                    <a href="{{ $twitterUrl }}" target="_blank" class="social-btn social-twitter" aria-label="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                                @if($facebookUrl)
                                    <a href="{{ $facebookUrl }}" target="_blank" class="social-btn social-facebook" aria-label="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if($websiteUrl)
                                    <a href="{{ $websiteUrl }}" target="_blank" class="social-btn social-website" aria-label="Sitio web">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="featured-section mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h4 fw-bold mb-1">Obra destacada</h2>
                    <p class="text-muted mb-0">Desliza para explorar una obra a la vez.</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="text-muted small">Explora</span>
                </div>
            </div>

            @if(count($artworks) > 0)
                <div class="featured-carousel">
                    <div class="featured-track" id="featuredTrack">
                        @foreach($artworks as $artwork)
                            <div class="featured-slide">
                                <div class="featured-media">
                                    <button class="carousel-edge carousel-left" type="button" data-carousel="featured" data-dir="prev" aria-label="Anterior">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="carousel-edge carousel-right" type="button" data-carousel="featured" data-dir="next" aria-label="Siguiente">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="featured-link">
                                        @if($artwork->image_path)
                                            <img
                                                src="{{ asset('storage/' . $artwork->image_path) }}"
                                                alt="{{ $artwork->title }}"
                                                class="featured-img"
                                            >
                                        @else
                                            <div class="featured-img-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="featured-overlay">
                                        <div>
                                            <div class="featured-title">{{ $artwork->title }}</div>
                                            @if($artwork->category)
                                                <div class="featured-category">{{ $artwork->category->name }}</div>
                                            @endif
                                        </div>
                                        <div class="featured-likes">
                                            <i class="fas fa-heart"></i>
                                            <span>{{ $artwork->likes()->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="carousel-dots carousel-dots-below" id="featuredDots">
                        @foreach($artworks as $index => $artwork)
                            <button type="button" class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-label="Ir a obra {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card text-center p-4 bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-images fa-3x text-secondary mb-3"></i>
                        <p class="text-muted">Aún no has subido ninguna obra de arte</p>
                        <a href="{{ route('artworks.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Subir nueva obra
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if($artist->bio)
            <div class="card border-0 shadow-sm mb-4 bio-card">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Sobre el artista</h2>
                    <p class="text-muted mb-0">{{ $artist->bio }}</p>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Todas las obras</h2>
            <a href="{{ route('artworks.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus me-1"></i>Subir nueva obra
            </a>
        </div>

        @if(count($artworks) > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                @foreach($artworks as $artwork)
                    <div class="col">
                        <div class="grid-card">
                            <a href="{{ route('artworks.show', $artwork->id) }}" class="grid-media">
                                @if($artwork->image_path)
                                    <img
                                        src="{{ asset('storage/' . $artwork->image_path) }}"
                                        alt="{{ $artwork->title }}"
                                        class="grid-img"
                                    >
                                @else
                                    <div class="grid-img-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="grid-body">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <h3 class="h6 fw-bold mb-1">
                                            <a href="{{ route('artworks.show', $artwork->id) }}" class="text-decoration-none text-dark">
                                                {{ $artwork->title }}
                                            </a>
                                        </h3>
                                        @if($artwork->category)
                                            <div class="artwork-category">{{ $artwork->category->name }}</div>
                                        @endif
                                    </div>
                                    <div class="artwork-likes">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ $artwork->likes()->count() }}</span>
                                    </div>
                                </div>
                                <p class="artwork-desc">{{ $artwork->description }}</p>
                                <div class="artwork-actions">
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('artworks.edit', $artwork->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $artwork->id }}"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card text-center p-4 bg-light border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-images fa-3x text-secondary mb-3"></i>
                    <p class="text-muted">Aún no has subido ninguna obra de arte</p>
                    <a href="{{ route('artworks.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus me-2"></i>Subir nueva obra
                    </a>
                </div>
            </div>
        @endif
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
        .artist-profile {
            color: var(--dark-color);
        }

        .profile-header {
            background: radial-gradient(circle at top right, rgba(107, 33, 168, 0.08), transparent 55%),
                linear-gradient(135deg, #ffffff 0%, #f7f3ff 100%);
            border: 1px solid rgba(107, 33, 168, 0.1);
        }

        .profile-avatar {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 6px solid rgba(107, 33, 168, 0.15);
            overflow: hidden;
            background: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            background: #f3e8ff;
        }

        .profile-bio-line {
            max-width: 640px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .profile-stat {
            padding: 0.6rem 1rem;
            background: #ffffff;
            border-radius: 0.75rem;
            border: 1px solid rgba(107, 33, 168, 0.1);
            min-width: 120px;
            text-align: center;
        }

        .profile-stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .profile-stat-label {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .profile-socials {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(107, 33, 168, 0.2);
            color: var(--primary-color);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 33, 168, 0.15);
            color: var(--dark-color);
        }

        .carousel-btn {
            border-radius: 999px;
            border: 1px solid rgba(107, 33, 168, 0.2);
            color: var(--primary-color);
        }

        .featured-section {
            padding: 1.5rem;
            border-radius: 1.25rem;
            background: #ffffff;
            border: 1px solid rgba(107, 33, 168, 0.08);
            box-shadow: 0 16px 40px rgba(107, 33, 168, 0.08);
        }

        .featured-carousel {
            position: relative;
            width: 100%;
            margin: 0 auto;
        }

        .featured-track {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            gap: 1rem;
        }

        .featured-track::-webkit-scrollbar {
            height: 8px;
        }

        .featured-track::-webkit-scrollbar-thumb {
            background: rgba(107, 33, 168, 0.25);
            border-radius: 999px;
        }

        .featured-slide {
            flex: 0 0 100%;
            scroll-snap-align: start;
        }

        .featured-media {
            position: relative;
            width: 100%;
            height: clamp(260px, 45vw, 420px);
            border-radius: 1.25rem;
            overflow: hidden;
            background: #f8f4ff;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.15);
        }

        .carousel-edge {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: none;
            background: rgba(255, 255, 255, 0.8);
            color: var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
            transition: transform 0.2s ease, background 0.2s ease;
            z-index: 2;
        }

        .carousel-edge:hover {
            background: #ffffff;
            transform: translateY(-50%) scale(1.05);
        }

        .carousel-left {
            left: 12px;
        }

        .carousel-right {
            right: 12px;
        }

        .featured-link {
            display: block;
            width: 100%;
            height: 100%;
        }

        .featured-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .featured-media:hover .featured-img {
            transform: scale(1.04);
        }

        .featured-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(107, 33, 168, 0.5);
        }

        .featured-overlay {
            position: absolute;
            inset: auto 0 0 0;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.75) 100%);
            color: #ffffff;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
        }

        .featured-title {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .featured-category {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.85;
        }

        .featured-likes {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            font-size: 0.9rem;
        }

        .bio-card {
            border-left: 6px solid rgba(107, 33, 168, 0.35);
        }

        .grid-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid rgba(107, 33, 168, 0.08);
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .grid-media {
            height: 220px;
            display: block;
            background: #f8f4ff;
        }

        .grid-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .grid-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: rgba(107, 33, 168, 0.5);
        }

        .grid-body {
            padding: 1rem 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }

        .artwork-category {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .artwork-likes {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.85rem;
            color: #ef4444;
            background: #fff5f7;
            padding: 0.2rem 0.5rem;
            border-radius: 999px;
        }

        .artwork-desc {
            font-size: 0.85rem;
            color: #6b7280;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: auto;
        }

        .artwork-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 0.75rem;
        }

        .carousel-dots-below {
            margin-top: 1rem;
        }

        .carousel-dots .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: none;
            background: rgba(107, 33, 168, 0.25);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .carousel-dots .dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
        }

        @media (max-width: 992px) {
            .profile-avatar {
                width: 180px;
                height: 180px;
            }
        }

        @media (max-width: 768px) {
            .profile-avatar {
                width: 160px;
                height: 160px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const track = document.getElementById('featuredTrack');
            const dotsWrap = document.getElementById('featuredDots');
            const navButtons = document.querySelectorAll('[data-carousel="featured"]');

            if (!track) {
                return;
            }

            const slides = Array.from(track.querySelectorAll('.featured-slide'));

            const scrollToIndex = (index) => {
                const slide = slides[index];
                if (!slide) {
                    return;
                }
                track.scrollTo({ left: slide.offsetLeft, behavior: 'smooth' });
            };

            navButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const dir = btn.getAttribute('data-dir');
                    const slideWidth = slides[0] ? slides[0].getBoundingClientRect().width : track.clientWidth;
                    const gap = 16;
                    const amount = dir === 'next' ? slideWidth + gap : -(slideWidth + gap);
                    track.scrollBy({ left: amount, behavior: 'smooth' });
                });
            });

            if (dotsWrap) {
                dotsWrap.addEventListener('click', (event) => {
                    const target = event.target;
                    if (target instanceof HTMLElement && target.matches('.dot')) {
                        const index = Number(target.getAttribute('data-index'));
                        scrollToIndex(index);
                    }
                });
            }

            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;

            track.addEventListener('pointerdown', (event) => {
                isDown = true;
                startX = event.pageX - track.offsetLeft;
                scrollLeft = track.scrollLeft;
                track.style.scrollBehavior = 'auto';
            });

            track.addEventListener('pointerleave', () => {
                isDown = false;
                track.style.scrollBehavior = 'smooth';
            });

            track.addEventListener('pointerup', () => {
                isDown = false;
                track.style.scrollBehavior = 'smooth';
            });

            track.addEventListener('pointermove', (event) => {
                if (!isDown) {
                    return;
                }
                event.preventDefault();
                const x = event.pageX - track.offsetLeft;
                const walk = (x - startX) * 1.2;
                track.scrollLeft = scrollLeft - walk;
            });

            const updateDots = () => {
                if (!dotsWrap) {
                    return;
                }
                const scrollPos = track.scrollLeft;
                let closestIndex = 0;
                let minDiff = Infinity;
                slides.forEach((slide, index) => {
                    const diff = Math.abs(slide.offsetLeft - scrollPos);
                    if (diff < minDiff) {
                        minDiff = diff;
                        closestIndex = index;
                    }
                });
                dotsWrap.querySelectorAll('.dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === closestIndex);
                });
            };

            track.addEventListener('scroll', () => {
                window.requestAnimationFrame(updateDots);
            });
        });
    </script>
</x-app-layout>
