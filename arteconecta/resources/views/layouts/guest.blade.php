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
                background-color: var(--secondary-color);
                background-image: linear-gradient(135deg, var(--secondary-color) 0%, var(--white-color) 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .auth-card {
                background-color: var(--white-color);
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(107, 33, 168, 0.1);
                overflow: hidden;
                max-width: 500px;
                width: 100%;
                padding: 0;
            }
            
            .auth-header {
                background-color: var(--primary-color);
                padding: 1.5rem;
                text-align: center;
            }
            
            .auth-header h1 {
                color: var(--white-color);
                font-size: 1.75rem;
                font-weight: 600;
                margin: 0;
            }
            
            .auth-body {
                padding: 2rem;
            }
            
            .form-label {
                color: var(--dark-color);
                font-weight: 500;
            }
            
            .form-control {
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
                border: 1px solid #e2e8f0;
            }
            
            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.25rem rgba(107, 33, 168, 0.25);
            }
            
            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                border-radius: 0.5rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
            }
            
            .btn-primary:hover, .btn-primary:focus {
                background-color: var(--dark-color);
                border-color: var(--dark-color);
            }
            
            .btn-secondary {
                background-color: transparent;
                border-color: var(--primary-color);
                color: var(--primary-color);
                border-radius: 0.5rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
            }
            
            .btn-secondary:hover, .btn-secondary:focus {
                background-color: var(--primary-color);
                color: var(--white-color);
            }
            
            .btn-link {
                color: var(--primary-color);
                text-decoration: none;
            }
            
            .btn-link:hover {
                color: var(--dark-color);
                text-decoration: underline;
            }
            
            .logo-container {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            
            .logo {
                font-size: 2rem;
                font-weight: 700;
                color: var(--primary-color);
                text-decoration: none;
            }
            
            .invalid-feedback {
                color: #ef4444;
                font-size: 0.875rem;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="auth-card">
                        <div class="auth-header">
                            <a href="/" class="logo text-white text-decoration-none">
                                <i class="fas fa-palette me-2"></i>ArteConecta
                            </a>
                        </div>
                        
                        <div class="auth-body">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap JS Bundle con Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
