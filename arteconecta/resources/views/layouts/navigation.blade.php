<!-- Navbar simplificado con estilo consistente -->
<nav class="navbar">
    <div class="navbar-container" style="display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        <!-- Logo y Enlaces a la izquierda -->
        <div style="display: flex; align-items: center;">
            <a href="/" class="logo" style="margin-right: 2rem;">ArteConecta</a>
            
            <!-- Enlaces principales en el navbar -->
            <div class="nav-links">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('artworks.index') }}" class="nav-link {{ request()->routeIs('artworks.index') ? 'active' : '' }}">Galería</a>
                <a href="{{ url('/#features') }}" class="nav-link {{ request()->routeIs('conocenos') ? 'active' : '' }}">Conócenos</a>
                <a href="{{ route('artists.index') }}" class="nav-link {{ request()->routeIs('artists.index') ? 'active' : '' }}">Artistas</a>
                <a href="{{ route('books.recommendations') }}" class="nav-link {{ request()->routeIs('books.recommendations') ? 'active' : '' }}">
                    <i class="fas fa-book me-1"></i> Biblioteca
                </a>
                @auth
                    @if(Auth::user()->isVisitor())
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-heart me-1"></i> Tus gustos
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        
        <!-- Área de autenticación/usuario alineada a la derecha -->
        <div class="auth-links" style="display: flex; align-items: center; gap: 10px;">
            @auth
                <!-- Botón para abrir el menú lateral -->
                <button type="button" class="btn-primary sidebar-toggle" id="openSidebar">
                    <i class="fas fa-user-circle me-2"></i>Perfil
                </button>
            @else
                <a href="{{ route('login') }}" class="btn-secondary">Iniciar sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Registrarse</a>
                @endif
            @endauth
        </div>
    </div>
</nav>

<!-- Menú lateral para opciones de usuario -->
@auth
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h5>{{ Auth::user()->name }}</h5>
        <button type="button" class="btn-close" id="closeSidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <div class="user-info mb-4">
            @if(Auth::user()->avatar_path)
                <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}" 
                    class="avatar" onerror="this.src='https://via.placeholder.com/64?text={{ substr(Auth::user()->name, 0, 1) }}'">
            @else
                <div class="avatar-placeholder">
                    <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            @endif
        </div>
        <ul class="sidebar-menu">
            @if (Auth::user()->isArtist())
                <li><a href="{{ route('artist.profile') }}"><i class="fas fa-user me-2"></i>Ver mi perfil</a></li>
                <li><a href="{{ route('artist.profile.edit') }}"><i class="fas fa-edit me-2"></i>Editar perfil</a></li>
                <li><a href="{{ route('artist.artworks') }}"><i class="fas fa-images me-2"></i>Mis obras</a></li>
                <li><a href="{{ route('artworks.create') }}"><i class="fas fa-plus-circle me-2"></i>Subir obra</a></li>
            @else
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-heart me-2"></i>Tus gustos</a></li>
                <li><a href="{{ route('artworks.index') }}"><i class="fas fa-images me-2"></i>Explorar galería</a></li>
                <li><a href="{{ route('artists.index') }}"><i class="fas fa-users me-2"></i>Artistas</a></li>
                <li><a href="{{ route('visitor.profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Editar perfil</a></li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
@endauth
