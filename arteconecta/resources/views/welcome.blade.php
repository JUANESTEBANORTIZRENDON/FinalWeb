<x-app-layout>
    <section class="container py-5 banter-section">
        <div class="banter-card p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="banter-badge text-uppercase" style="background: rgba(255,140,0,0.16); color: #ff8c00;">BANTER Design Studio</span>
                    <h1 class="banter-title display-5 mt-3 mb-3">Conecta con el arte independiente</h1>
                    <p class="fs-5 text-muted mb-4">Plataforma para artistas emergentes y amantes del arte. Descubre, comparte y construye comunidad con identidad visual urbana premium.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('artworks.index') }}" class="banter-btn-primary">Empieza ahora</a>
                        <a href="{{ route('artists.index') }}" class="banter-btn-outline">Explorar artistas</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banter-card p-4 h-100">
                        <h2 class="h4 fw-bold mb-3">Por que BANTER</h2>
                        <ul class="mb-0 ps-3">
                            <li class="mb-2">Perfiles de artista con presencia visual fuerte.</li>
                            <li class="mb-2">Feed curado con interacciones rapidas y claras.</li>
                            <li class="mb-2">Conexion real entre artistas y visitantes.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5 banter-section">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="banter-card p-4 h-100">
                    <h3 class="h5 fw-bold mb-3">Perfiles vivos</h3>
                    <p class="text-muted mb-0">Haz que tu identidad de artista se vea profesional, moderna y memorable.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="banter-card p-4 h-100">
                    <h3 class="h5 fw-bold mb-3">Descubrimiento rapido</h3>
                    <p class="text-muted mb-0">Navega por obras, categorias y creadores con una experiencia fluida en cualquier dispositivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="banter-card p-4 h-100">
                    <h3 class="h5 fw-bold mb-3">Comunidad activa</h3>
                    <p class="text-muted mb-0">Likes, comentarios y seguimiento para potenciar la exposicion del talento independiente.</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
