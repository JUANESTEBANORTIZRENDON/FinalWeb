<x-app-layout>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4 banter-section">
            <div>
                <h1 class="banter-title display-6">Feed de Obras</h1>
                <p class="text-muted mb-0">Explora la galeria publica con identidad BANTER.</p>
            </div>
            <span class="banter-badge" style="background: rgba(125,26,122,0.14); color: #7d1a7a;">{{ $artworks->total() }} publicaciones</span>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($artworks->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 banter-section">
                @foreach($artworks as $artwork)
                    <div class="col">
                        <article class="banter-card h-100 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $artwork->image_path) }}" class="w-100" alt="{{ $artwork->title }}" style="height: 240px; object-fit: cover;">
                                @if($artwork->category)
                                    <span class="banter-badge position-absolute top-0 end-0 m-2" style="background: rgba(255,255,255,0.85); color: #4a0e5c;">
                                        {{ $artwork->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-3 d-flex flex-column h-100">
                                <h2 class="h5 fw-bold mb-1">{{ $artwork->title }}</h2>
                                <p class="text-muted small mb-2">
                                    Por <a href="{{ route('artist.profile.public', $artwork->artist_id) }}" class="text-decoration-none fw-semibold">{{ $artwork->artist->name }}</a>
                                </p>

                                @if($artwork->description)
                                    <p class="text-muted small mb-3">{{ Str::limit($artwork->description, 95) }}</p>
                                @endif

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @auth
                                            <button class="btn btn-sm like-button banter-btn-outline {{ $artwork->user_liked ? 'liked' : '' }}"
                                                    onclick="toggleLike('{{ $artwork->id }}')" id="like-btn-{{ $artwork->id }}">
                                                <i class="fas fa-heart"></i>
                                                <span id="likes-count-{{ $artwork->id }}">{{ $artwork->likes_count }}</span>
                                            </button>
                                            <a href="{{ route('artworks.show', $artwork->id) }}#comments" class="btn btn-sm banter-btn-outline">
                                                <i class="fas fa-comment"></i>
                                                <span>{{ $artwork->comments_count }}</span>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-sm banter-btn-outline">
                                                <i class="fas fa-heart"></i>{{ $artwork->likes_count }}
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-sm banter-btn-outline">
                                                <i class="fas fa-comment"></i>{{ $artwork->comments_count }}
                                            </a>
                                        @endauth
                                    </div>
                                    <a href="{{ route('artworks.show', $artwork->id) }}" class="btn btn-sm banter-btn-primary">Ver</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4 banter-section">
                {{ $artworks->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info banter-section">
                <p class="mb-0 text-center">No hay obras publicas disponibles en este momento.</p>
            </div>
        @endif
    </div>

    <script>
        function toggleLike(artworkId) {
            const likeButton = document.getElementById(`like-btn-${artworkId}`);
            const likesCountElement = document.getElementById(`likes-count-${artworkId}`);
            const currentLikes = parseInt(likesCountElement.textContent, 10);

            if (likeButton.classList.contains('liked')) {
                likeButton.classList.remove('liked');
                likesCountElement.textContent = Math.max(0, currentLikes - 1);
            } else {
                likeButton.classList.add('liked');
                likesCountElement.textContent = currentLikes + 1;
            }

            likeButton.disabled = true;

            fetch(`/artworks/${artworkId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then((data) => {
                likesCountElement.textContent = data.likesCount;
                likeButton.classList.toggle('liked', data.liked);
            })
            .catch(() => {
                if (likeButton.classList.contains('liked')) {
                    likeButton.classList.remove('liked');
                    likesCountElement.textContent = Math.max(0, parseInt(likesCountElement.textContent, 10) - 1);
                } else {
                    likeButton.classList.add('liked');
                    likesCountElement.textContent = parseInt(likesCountElement.textContent, 10) + 1;
                }
            })
            .finally(() => {
                likeButton.disabled = false;
            });
        }
    </script>
</x-app-layout>
