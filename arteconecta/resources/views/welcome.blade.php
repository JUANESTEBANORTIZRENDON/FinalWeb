<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="ArteConecta - Plataforma para artistas emergentes y amantes del arte">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ArteConecta') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS (CDN) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Fontawesome para íconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Estilos personalizados para ArteConecta -->
        <style>
            :root {
                --primary-color: #6b21a8; /* Morado */
                --secondary-color: #f3e8ff; /* Morado muy claro */
                --accent-color-1: #f9a8d4; /* Rosado claro */
                --accent-color-2: #93c5fd; /* Azul claro */
                --dark-color: #1e1b4b; /* Morado oscuro casi negro */
                --white-color: #ffffff;
                --gray-color: #f8fafc;
            }
            
            body {
                font-family: 'Montserrat', 'Figtree', sans-serif;
                color: var(--dark-color);
            }
            
            .bg-primary {
                background-color: var(--primary-color);
            }
            
            .bg-secondary {
                background-color: var(--secondary-color);
            }
            
            .bg-accent-1 {
                background-color: var(--accent-color-1);
            }
            
            .bg-accent-2 {
                background-color: var(--accent-color-2);
            }
            
            .text-primary {
                color: var(--primary-color);
            }
            
            .text-secondary {
                color: var(--secondary-color);
            }
            
            .text-accent-1 {
                color: var(--accent-color-1);
            }
            
            .text-accent-2 {
                color: var(--accent-color-2);
            }
            
            .border-primary {
                border-color: var(--primary-color);
            }
            
            /* Hero section */
            .hero {
                background-image: linear-gradient(rgba(107, 33, 168, 0.8), rgba(30, 27, 75, 0.9)), url('/img/hero-bg.jpg');
                background-size: cover;
                background-position: center;
                min-height: 80vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
            
            .hero h1 {
                font-size: 3.5rem;
                font-weight: 700;
                color: var(--white-color);
                margin-bottom: 1rem;
            }
            
            .hero p {
                font-size: 1.25rem;
                color: var(--secondary-color);
                margin-bottom: 2rem;
            }
            
            /* Botones */
            .btn-primary {
                background-color: var(--primary-color);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
            }
            
            .btn-primary:hover {
                background-color: var(--dark-color);
                transform: translateY(-2px);
            }
            
            .btn-secondary {
                background-color: transparent;
                color: var(--secondary-color);
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                border: 2px solid var(--secondary-color);
                transition: all 0.3s ease;
                display: inline-block;
            }
            
            .btn-secondary:hover {
                background-color: var(--secondary-color);
                color: var(--primary-color);
                transform: translateY(-2px);
            }
            
            /* Tarjetas de características */
            .feature-card {
                padding: 2rem;
                border-radius: 1rem;
                background-color: white;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
            
            .feature-icon {
                height: 3rem;
                width: 3rem;
                background-color: var(--secondary-color);
                color: var(--primary-color);
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
            }
            
            /* Navbar */
            .navbar {
                background-color: var(--primary-color);
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 50;
            }
            
            .navbar-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1rem;
            }
            
            .logo {
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
                text-decoration: none;
            }
            
            .nav-links {
                display: flex;
                gap: 1.5rem;
            }
            
            .nav-link {
                color: var(--secondary-color);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }
            
            .nav-link:hover {
                color: white;
            }
            
            .nav-link.active {
                color: white;
                position: relative;
            }
            
            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 100%;
                height: 2px;
                background-color: var(--accent-color-1);
            }
            
            .auth-links {
                display: flex;
                gap: 1rem;
            }
            
            .user-menu {
                position: relative;
            }
            
            .user-dropdown {
                position: absolute;
                top: 100%;
                right: 0;
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                width: 200px;
                z-index: 20;
                padding: 0.5rem 0;
                margin-top: 0.5rem;
                display: none;
            }
            
            .user-menu:hover .user-dropdown {
                display: block;
            }
            
            .user-dropdown-link {
                display: block;
                padding: 0.5rem 1rem;
                color: var(--dark-color);
                text-decoration: none;
                transition: background-color 0.3s ease;
            }
            
            .user-dropdown-link:hover {
                background-color: var(--secondary-color);
            }
            
            /* Secciones */
            .section {
                padding: 5rem 0;
            }
            
            .section-title {
                font-size: 2.25rem;
                font-weight: 700;
                color: var(--primary-color);
                text-align: center;
                margin-bottom: 3rem;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .nav-links {
                    display: none;
                }
                
                .hero h1 {
                    font-size: 2.5rem;
                }
                
                .hero p {
                    font-size: 1rem;
                }
                
                .section {
                    padding: 3rem 0;
                }
                
                .section-title {
                    font-size: 1.75rem;
                }
            }
            
            /* Footer */
            .footer {
                background-color: var(--dark-color);
                color: var(--secondary-color);
                padding: 3rem 0;
            }
            
            .footer-links {
                display: flex;
                justify-content: center;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            
            .footer-link {
                color: var(--secondary-color);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .footer-link:hover {
                color: white;
            }
            
            .social-links {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            
            .social-link {
                height: 2.5rem;
                width: 2.5rem;
                background-color: var(--primary-color);
                color: white;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background-color 0.3s ease;
            }
            
            .social-link:hover {
                background-color: var(--accent-color-1);
            }
            
            /* Estilos para el sidebar */
            .sidebar {
                position: fixed;
                top: 0;
                right: -300px;
                width: 300px;
                height: 100%;
                background-color: white;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
                z-index: 100;
                transition: right 0.3s ease;
                overflow-y: auto;
            }
            
            .sidebar.active {
                right: 0;
            }
            
            .sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.5rem;
                background-color: var(--primary-color);
                color: white;
            }
            
            .sidebar-header h5 {
                margin: 0;
                font-weight: 600;
            }
            
            .btn-close {
                background: transparent;
                border: none;
                color: white;
                font-size: 1.2rem;
                cursor: pointer;
            }
            
            .sidebar-content {
                padding: 1.5rem;
            }
            
            .user-info {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid var(--secondary-color);
            }
            
            .avatar-placeholder {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background-color: var(--secondary-color);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                color: var(--primary-color);
                font-weight: bold;
            }
            
            .sidebar-menu {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .sidebar-menu li {
                margin-bottom: 0.5rem;
            }
            
            .sidebar-menu a, .sidebar-logout {
                display: block;
                padding: 0.75rem 1rem;
                color: var(--dark-color);
                text-decoration: none;
                border-radius: 0.25rem;
                transition: all 0.2s ease;
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                font-size: 1rem;
                cursor: pointer;
            }
            
            .sidebar-menu a:hover, .sidebar-logout:hover {
                background-color: var(--secondary-color);
            }
            
            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 99;
                display: none;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        </style>
    </head>
    <body>
        <!-- Incluir el mismo navegador que usa el resto de la aplicación -->
        @include('layouts.navigation')

        <!-- Hero Section -->
        <section class="hero">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem; text-align: center;">
                <h1>Conecta con el arte independiente</h1>
                <p>Plataforma para artistas emergentes y amantes del arte. Descubre, comparte y conecta.</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="{{ route('artworks.index') }}" class="btn-primary">Empieza ahora</a>
                    <a href="#features" class="btn-secondary">Conoce más</a>
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section id="features" class="section bg-secondary">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
                <h2 class="section-title">Características principales</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-color);">Perfiles de artistas</h3>
                        <p style="color: var(--dark-color); line-height: 1.6;">
                            Crea tu perfil como artista y muestra tu trabajo al mundo. Personaliza tu bio, sube tus mejores obras y conectá con otros creadores.  
                        </p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-images fa-lg"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-color);">Galería personal</h3>
                        <p style="color: var(--dark-color); line-height: 1.6;">
                            Organiza tus creaciones en una galería profesional. Añade detalles como técnica, dimensiones y descripciones para cada obra.
                        </p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: var(--primary-color);">Comunidad activa</h3>
                        <p style="color: var(--dark-color); line-height: 1.6;">
                            Interactúa con otros artistas y amantes del arte. Comenta, da me gusta y sigue a tus creadores favoritos para estar al día.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
                <div class="footer-links">
                    <a href="#" class="footer-link">Sobre nosotros</a>
                    <a href="#" class="footer-link">Términos de servicio</a>
                    <a href="#" class="footer-link">Política de privacidad</a>
                    <a href="#" class="footer-link">Contacto</a>
                </div>
                
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-pinterest-p"></i></a>
                </div>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <p style="color: var(--secondary-color); opacity: 0.8;">&copy; {{ date('Y') }} ArteConecta. Todos los derechos reservados.</p>
                    <p style="color: var(--secondary-color); opacity: 0.6; margin-top: 0.5rem; font-size: 0.875rem;">Impulsado por Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
                </div>
            </div>
        </footer>

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
                        <li><a href="#"><i class="fas fa-heart me-2"></i>Favoritos</a></li>
                        <li><a href="#"><i class="fas fa-users me-2"></i>Artistas seguidos</a></li>
                    @endif
                    <li><a href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2"></i>Configuración</a></li>
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
        
        <!-- JavaScript para el menú lateral -->
        <script>
            // Esperamos a que el DOM esté completamente cargado
            window.addEventListener('load', function() {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                const openSidebarBtn = document.getElementById('openSidebar');
                const closeSidebarBtn = document.getElementById('closeSidebar');
                
                if (!sidebar || !sidebarOverlay || !openSidebarBtn || !closeSidebarBtn) {
                    console.error('Alguno de los elementos necesarios para el sidebar no fue encontrado');
                    return;
                }
                
                console.log('Elementos del sidebar encontrados, inicializando...');
                
                // Función para abrir el sidebar
                function openSidebar() {
                    console.log('Abriendo sidebar...');
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
                
                // Función para cerrar el sidebar
                function closeSidebar() {
                    console.log('Cerrando sidebar...');
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Event listeners
                openSidebarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSidebar();
                });
                
                closeSidebarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeSidebar();
                });
                
                sidebarOverlay.addEventListener('click', function() {
                    closeSidebar();
                });
                
                // Cerrar el sidebar con la tecla Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                        closeSidebar();
                    }
                });
            });
        </script>
    </body>
</html>
