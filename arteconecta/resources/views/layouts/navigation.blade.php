<nav class="navbar">
    <div class="navbar-container">
        <div class="d-flex align-items-center gap-3">
            <a href="/" class="banter-brand" aria-label="Banter Home">
                <img src="{{ asset('images/banter-logo.png') }}" alt="Banter" class="banter-logo">
            </a>

            <div class="nav-links">
                <a href="/" class="banter-nav-link {{ request()->is('/') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('artists.index') }}" class="banter-nav-link {{ request()->routeIs('artists.index') ? 'active' : '' }}">Artistas</a>
                <a href="{{ route('artworks.index') }}" class="banter-nav-link {{ request()->routeIs('artworks.index') ? 'active' : '' }}">Obras</a>
                <a href="{{ route('about') }}" class="banter-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Quienes somos</a>
                <a href="{{ route('books.recommendations') }}" class="banter-nav-link {{ request()->routeIs('books.recommendations') ? 'active' : '' }}">
                    <i class="fas fa-book me-1"></i>Biblioteca
                </a>
                @auth
                    @if(Auth::user()->isVisitor())
                        <a href="{{ route('dashboard') }}" class="banter-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-heart me-1"></i>Tus gustos
                        </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="banter-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield me-1"></i>Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="auth-links">
            @auth
                <button type="button" class="banter-btn-primary sidebar-toggle" id="openSidebar">
                    <i class="fas fa-user-circle"></i>Perfil
                </button>
            @else
                <a href="{{ route('login') }}" class="banter-btn-outline">Iniciar sesion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="banter-btn-primary">Registrarse</a>
                @endif
            @endauth
        </div>
    </div>
</nav>

@auth
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
        <button type="button" class="btn-close" id="closeSidebar" aria-label="Cerrar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <div class="user-info">
            @if(Auth::user()->avatar_path)
                <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}" class="avatar">
            @else
                <div class="avatar-placeholder">
                    <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>
        <ul class="sidebar-menu">
            @if (Auth::user()->isArtist())
                <li><a href="{{ route('artist.profile') }}"><i class="fas fa-user me-2"></i>Ver mi perfil</a></li>
                <li><a href="{{ route('artist.profile.edit') }}"><i class="fas fa-edit me-2"></i>Editar perfil</a></li>
                <li><a href="{{ route('artist.artworks') }}"><i class="fas fa-images me-2"></i>Mis obras</a></li>
                <li><a href="{{ route('artworks.create') }}"><i class="fas fa-plus-circle me-2"></i>Subir obra</a></li>
            @elseif (Auth::user()->isAdmin())
                <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield me-2"></i>Panel Admin</a></li>
                <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Mi perfil</a></li>
            @else
                <li><a href="{{ route('dashboard') }}"><i class="fas fa-heart me-2"></i>Tus gustos</a></li>
                <li><a href="{{ route('artworks.index') }}"><i class="fas fa-images me-2"></i>Explorar galeria</a></li>
                <li><a href="{{ route('artists.index') }}"><i class="fas fa-users me-2"></i>Artistas</a></li>
                <li><a href="{{ route('visitor.profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Editar perfil</a></li>
            @endif
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesion
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
@endauth
