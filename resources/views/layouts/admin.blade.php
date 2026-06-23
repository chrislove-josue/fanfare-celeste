<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin - Fanfare Céleste')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f6f7fb;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #283593, #3946B2);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar .brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .brand a {
            color: #fff;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar .brand a i {
            font-size: 1.5rem;
        }
        
        .sidebar .brand .subtitle {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.7);
            margin-top: 0.25rem;
        }
        
        .sidebar .nav-section {
            padding: 1rem 0.75rem;
        }
        
        .sidebar .nav-section .section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.5);
            padding: 0.5rem 0.75rem;
            margin-top: 0.5rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin: 0.1rem 0;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
        }
        
        .sidebar .user-info {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }
        
        .sidebar .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .sidebar .user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
        }
        
        .sidebar .logout-btn {
            padding: 0.5rem 1rem;
            margin: 0.5rem 1rem;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            width: calc(100% - 2rem);
            transition: all 0.2s ease;
        }
        
        .sidebar .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .content {
            margin-left: 260px;
            padding: 1.5rem;
            min-height: 100vh;
        }
        
        .topbar {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }
        
        .topbar .page-title h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .topbar .page-title .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 0.85rem;
        }
        
        .topbar .page-title .breadcrumb-item a {
            color: #3946B2;
            text-decoration: none;
        }
        
        .topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .topbar .topbar-right .badge-notif {
            position: relative;
        }
        
        .topbar .topbar-right .badge-notif .badge-count {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            padding: 0.15rem 0.4rem;
            font-size: 0.65rem;
        }
        
        /* Mobile toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #3946B2;
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .overlay.active {
                display: block;
            }
        }
        
        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        /* Card styles */
        .stat-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid #3946B2;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fff;
        }
        
        .stat-card .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.85rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-music"></i>
                <div>
                    <div>Fanfare Céleste</div>
                    <div class="subtitle">Administration</div>
                </div>
            </a>
        </div>

        <nav class="nav-section">
            <div class="section-title">Navigation</div>
            
            @php
                $routeName = request()->route()->getName();
                $isActive = function($routes) use ($routeName) {
                    if (is_array($routes)) {
                        return in_array($routeName, $routes) ? 'active' : '';
                    }
                    return $routeName === $routes ? 'active' : '';
                };
            @endphp

            <a class="nav-link {{ $isActive('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-pie"></i> Tableau de bord
            </a>

            @can('manage members')
            <a class="nav-link {{ $isActive(['admin.members.index', 'admin.members.create', 'admin.members.edit', 'admin.members.show']) }}" href="{{ route('admin.members.index') }}">
                <i class="fas fa-users"></i> Membres
            </a>
            @endcan

            @can('manage finances')
            <a class="nav-link {{ $isActive(['admin.financial.dashboard', 'admin.financial.transactions', 'admin.financial.contributions']) }}" href="{{ route('admin.financial.dashboard') }}">
                <i class="fas fa-coins"></i> Finances
            </a>
            @endcan

            @can('manage events')
            <a class="nav-link {{ $isActive(['admin.events.index', 'admin.events.create', 'admin.events.edit', 'admin.events.calendar']) }}" href="{{ route('admin.events.index') }}">
                <i class="fas fa-calendar-alt"></i> Événements
            </a>
            @endcan

            @can('manage gallery')
            <a class="nav-link {{ $isActive(['admin.gallery.index', 'admin.gallery.create', 'admin.gallery.edit']) }}" href="{{ route('admin.gallery.index') }}">
                <i class="fas fa-images"></i> Galerie
            </a>
            @endcan

            @can('manage documents')
            <a class="nav-link {{ $isActive(['admin.documents.index', 'admin.documents.create']) }}" href="{{ route('admin.documents.index') }}">
                <i class="fas fa-file-alt"></i> Documents
            </a>
            @endcan

            <a class="nav-link {{ $isActive(['my.profile']) }}" href="{{ route('my.profile') }}">
                <i class="fas fa-user-circle"></i> Mon profil
            </a>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 0.5rem 0.75rem;">

            <div class="section-title">Gestion</div>

            @can('manage committee')
            <a class="nav-link {{ $isActive(['admin.committee.index', 'admin.committee.create', 'admin.committee.edit']) }}" href="{{ route('admin.committee.index') }}">
                <i class="fas fa-user-tie"></i> Bureau exécutif
            </a>
            @endcan

            @can('view reports')
            <a class="nav-link {{ $isActive(['admin.reports.members', 'admin.reports.financial', 'admin.reports.events']) }}" href="{{ route('admin.reports.members') }}">
                <i class="fas fa-chart-bar"></i> Rapports
            </a>
            @endcan
        </nav>

        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">
                @if(auth()->user()->hasRole('admin'))
                    Administrateur
                @elseif(auth()->user()->hasRole('manager'))
                    Gestionnaire
                @else
                    Membre
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </button>
        </form>
    </aside>

    <!-- Main Content -->
    <div class="content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="page-title">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>@yield('page-title', 'Dashboard')</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="topbar-right">
                <span class="text-muted small">
                    <i class="fas fa-shield-alt"></i> Sécurisé
                </span>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        });
        
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });
    </script>
    
    @stack('scripts')
</body>
</html>