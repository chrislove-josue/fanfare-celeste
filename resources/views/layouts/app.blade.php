<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fanfare Céleste')</title>
    <link rel="shortcut icon" href="{{ asset('storage/logo/logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3946B2;
            --secondary-color: #40A81B;
            --accent-color: #2F8DCD;
            --fc-gold: #B8905C;
            --fc-cream: #FEFAF3;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            background: var(--fc-cream);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #283593);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #283593);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26,35,126,0.3);
        }
        
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .footer {
            background: linear-gradient(135deg, #0d1b3e, #1a237e);
            color: white;
            padding: 3rem 0 1rem 0;
            margin-top: 3rem;
        }
        
        .social-icon {
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            transform: translateY(-3px);
            color: var(--secondary-color) !important;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="gap-2 navbar-brand d-flex align-items-center" href="{{ route('home') }}">
    <img src="{{ asset('storage/logo/logo-blanc.jpeg') }}" 
         alt="Fanfare Céleste" 
         style="height: 80px; width: 150px;">
    <span>FCTDA</span>
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}"><i class="fas fa-info-circle"></i> À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.events.index') }}"><i class="fas fa-calendar-alt"></i> Événements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.gallery.index') }}"><i class="fas fa-images"></i> Galerie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope"></i> Contact</a>
                    </li>

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('my.profile') }}"><i class="fas fa-user-circle"></i> Mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('my.card') }}"><i class="fas fa-id-card"></i> Ma carte</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Administration</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="px-3 text-white nav-link btn btn-primary" href="{{ route('membership.apply') }}">
                                <i class="fas fa-hand-peace"></i> Adhérer
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main style="margin-top: 76px;">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="mb-4 col-md-4">
                    <p>Fanfare Céleste La Trompette de Dieu de l'Atlantique,
                    une fanfare au service de Dieu et de la communauté chrétienne</p>
                </div>
                <div class="mb-4 col-md-4">
                    <h5>Contact Rapide</h5>
                    <p>
                        <i class="fas fa-phone"></i> +229 97 00 00 00<br>
                        <i class="fas fa-envelope"></i> contact@fanfareceleste.org<br>
                        <i class="fab fa-whatsapp"></i> +229 97 00 00 00
                    </p>
                </div>
                <div class="mb-4 col-md-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white social-icon"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white social-icon"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white social-icon"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; 2021 - {{ date('Y') }} Fanfare Céleste - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
    @stack('scripts')
</body>
</html>