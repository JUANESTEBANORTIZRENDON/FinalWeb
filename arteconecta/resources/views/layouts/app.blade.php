<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ArteConecta') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS (CDN) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Fontawesome para iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
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
                font-family: 'Montserrat', sans-serif;
                background-color: #f8f9fa;
                min-height: 100vh;
                color: var(--dark-color);
            }

            /* Estilos para botones */
            .btn-primary {
                background-color: var(--primary-color);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--dark-color);
                transform: translateY(-2px);
                border-color: var(--dark-color);
            }
            
            .btn-secondary {
                background-color: transparent;
                color: var(--secondary-color);
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                text-decoration: none;
            }
            
            .btn-secondary:hover {
                color: white;
            }
            
            .btn-outline {
                background-color: transparent;
                color: white;
                border: 1px solid var(--secondary-color);
                padding: 0.5rem 1rem;
                border-radius: 0.4rem;
                font-weight: 500;
                transition: all 0.3s ease;
                display: inline-block;
                text-decoration: none;
                cursor: pointer;
                margin-left: 0.5rem;
            }
            
            .btn-outline:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: white;
                border-color: white;
            }

            .text-primary {
                color: var(--primary-color) !important;
            }
            
            /* Estilos del Navbar */
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
            
            .navbar-left {
                margin-right: 20px;
                flex-shrink: 0;
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
                justify-content: center;
                flex-grow: 1;
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
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="py-4">
            {{ $slot }}
        </main>
        
        <!-- Bootstrap JS Bundle con Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- JavaScript para el menú lateral -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                const openSidebarBtn = document.getElementById('openSidebar');
                const closeSidebarBtn = document.getElementById('closeSidebar');
                
                // Función para abrir el sidebar
                function openSidebar() {
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
                
                // Función para cerrar el sidebar
                function closeSidebar() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Event listeners
                if (openSidebarBtn) {
                    openSidebarBtn.addEventListener('click', openSidebar);
                }
                
                if (closeSidebarBtn) {
                    closeSidebarBtn.addEventListener('click', closeSidebar);
                }
                
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeSidebar);
                }
                
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
