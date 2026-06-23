<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration - Fanfare Céleste')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================
                   VARIABLES
                ============================================ */
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed: 70px;
            --primary: #3946B2;
            --primary-dark: #283593;
            --primary-light: #5c6bd4;
            --secondary: #40A81B;
            --accent: #FF6B35;
            --danger: #dc3545;
            --warning: #ffc107;
            --success: #28a745;
            --info: #17a2b8;
            --bg-body: #f0f2f8;
            --text-dark: #1a1a2e;
            --text-muted: #6c757d;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
            --radius: 12px;
            --radius-sm: 8px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ============================================
                   RESET & BASE
                ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            overflow-x: hidden;
            color: var(--text-dark);
        }

        /* ============================================
                   SIDEBAR
                ============================================ */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            background: linear-gradient(180deg, #1a1a3e 0%, #283593 50%, #3946B2 100%);
            color: #fff;
            transition: var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 30px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Brand - Fixe en haut */
        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
            background: linear-gradient(180deg, #1a1a3e 0%, #283593 50%, #3946B2 100%);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sidebar-brand .logo-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-brand a {
            color: #fff;
            text-decoration: none;
        }

        /* Sidebar Navigation - Scrollable */
        .sidebar-nav {
            padding: 0.75rem 0.75rem 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.35);
            padding: 0.75rem 0.75rem 0.5rem;
            font-weight: 600;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.6rem 0.75rem;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            margin: 0.1rem 0;
            cursor: pointer;
        }

        .sidebar-nav .nav-link i {
            width: 1.5rem;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            box-shadow: inset 3px 0 0 #40A81B;
        }

        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
        }

        .sidebar-nav .nav-link .chevron {
            margin-left: auto;
            font-size: 0.7rem;
            transition: var(--transition);
        }

        .sidebar-nav .nav-link .chevron.open {
            transform: rotate(180deg);
        }

        /* Submenu */
        .sidebar-nav .sub-menu {
            padding-left: 2.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-nav .sub-menu.open {
            max-height: 500px;
        }

        .sidebar-nav .sub-menu .nav-link {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-nav .sub-menu .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-nav .sub-menu .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }

        /* Sidebar Divider */
        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin: 0.5rem 0.75rem;
        }

        /* Sidebar User - Fixe en bas */
        .sidebar-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            background: linear-gradient(180deg, #283593 0%, #1a1a3e 100%);
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        .sidebar-user .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-user .user-name {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .sidebar-user .user-role {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
        }

        .sidebar-user .logout-btn {
            background: rgba(255,255,255,0.08);
            border: none;
            color: rgba(255,255,255,0.7);
            padding: 0.4rem 0.75rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            font-size: 0.8rem;
            width: 100%;
            text-align: center;
        }

        .sidebar-user .logout-btn:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        /* Scrollbar pour la sidebar */
        .sidebar::-webkit-scrollbar {
            width: 3px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        /* ============================================
                   MAIN CONTENT
                ============================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* ============================================
                   TOPBAR - Fixe en haut
                ============================================ */
        .topbar {
            background: #fff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-left .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-dark);
            padding: 0.3rem 0.5rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            display: none;
        }

        .topbar-left .sidebar-toggle:hover {
            background: var(--bg-body);
        }

        .topbar-left .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .topbar-left .page-title small {
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--text-muted);
            margin-left: 0.5rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-right .search-box {
            position: relative;
        }

        .topbar-right .search-box input {
            background: var(--bg-body);
            border: none;
            border-radius: 20px;
            padding: 0.4rem 1rem 0.4rem 2.2rem;
            font-size: 0.8rem;
            width: 200px;
            transition: var(--transition);
            outline: none;
        }

        .topbar-right .search-box input:focus {
            width: 280px;
            box-shadow: 0 0 0 2px rgba(57, 70, 178, 0.2);
        }

        .topbar-right .search-box i {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .topbar-right .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.1rem;
            padding: 0.3rem;
            border-radius: 50%;
            transition: var(--transition);
        }

        .topbar-right .notification-btn:hover {
            background: var(--bg-body);
            color: var(--text-dark);
        }

        .topbar-right .notification-btn .badge-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .topbar-right .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.3rem 0.5rem 0.3rem 0.3rem;
            border-radius: 30px;
            transition: var(--transition);
            cursor: pointer;
        }

        .topbar-right .user-dropdown:hover {
            background: var(--bg-body);
        }

        .topbar-right .user-dropdown .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .topbar-right .user-dropdown .user-info {
            text-align: left;
        }

        .topbar-right .user-dropdown .user-info .name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .topbar-right .user-dropdown .user-info .role {
            font-size: 0.65rem;
            color: var(--text-muted);
        }

        /* ============================================
                   PAGE CONTENT - Scrollable
                ============================================ */
        .page-content {
            padding: 1.5rem;
            flex: 1;
            overflow-y: auto;
        }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 992px) {
            .topbar-left .sidebar-toggle {
                display: block;
            }

            .topbar-right .search-box input {
                width: 140px;
            }

            .topbar-right .search-box input:focus {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0.5rem 1rem;
            }

            .topbar-left .page-title {
                font-size: 0.9rem;
            }

            .topbar-left .page-title small {
                display: none;
            }

            .topbar-right .search-box {
                display: none;
            }

            .page-content {
                padding: 1rem;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1045;
            }

            .overlay.active {
                display: block;
            }
        }

        /* ============================================
                   UTILITIES
                ============================================ */
        .text-primary-custom {
            color: var(--primary);
        }
        .bg-primary-custom {
            background: var(--primary);
        }
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        /* ============================================
                   CUSTOM SCROLLBAR FOR CONTENT
                ============================================ */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-body);
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c7d0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0a7b0;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ============================================
    OVERLAY (Mobile)
    ============================================ -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- ============================================
    SIDEBAR
    ============================================ -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand - Fixe en haut -->
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-3">
                <div class="logo-icon">
                    <i class="fas fa-music"></i>
                </div>
                <div>
                    <div class="brand-text">Fanfare Céleste</div>
                    <div class="brand-sub">Administration</div>
                </div>
            </a>
        </div>

        <!-- Navigation - Scrollable -->
        <nav class="sidebar-nav">
            @php
                $routeName = request()->route()->getName();
                $isActive = function($routes) use ($routeName) {
                    if (is_array($routes)) {
                        return in_array($routeName, $routes) ? 'active' : '';
                    }
                    return $routeName === $routes ? 'active' : '';
                };
            @endphp

            <!-- ===== DASHBOARD ===== -->
            <div class="nav-label">Navigation</div>

            <a class="nav-link {{ $isActive('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-th-large"></i>
                <span>Tableau de bord</span>
            </a>

            <!-- ===== GESTION DES MEMBRES ===== -->
            <div class="nav-label">Gestion des membres</div>

            @can('manage members')
            <a class="nav-link {{ $isActive(['admin.members.index', 'admin.members.create', 'admin.members.edit', 'admin.members.show']) }}" href="{{ route('admin.members.index') }}">
                <i class="fas fa-users"></i>
                <span>Tous les membres</span>
                <span class="badge bg-secondary">{{ \App\Models\Member::count() }}</span>
            </a>
            @endcan

            @can('create members')
            <a class="nav-link {{ $isActive('admin.members.index') }}" href="{{ route('admin.members.index') }}">
                <i class="fas fa-user"></i>
                <span>Tous les membres</span>
            </a>
            @endcan

            @can('create members')
            <a class="nav-link {{ $isActive('admin.members.create') }}" href="{{ route('admin.members.create') }}">
                <i class="fas fa-user-plus"></i>
                <span>Ajouter un membre</span>
            </a>
            @endcan

            @can('approve members')
            <a class="nav-link {{ $isActive(['admin.members.approve', 'admin.members.reject']) }}" href="{{ route('admin.members.index', ['status' => 'pending']) }}">
                <i class="fas fa-user-check"></i>
                <span>Approbations</span>
                <span class="badge bg-warning">{{ \App\Models\Member::where('status', 'pending')->count() }}</span>
            </a>
            @endcan

            @can('manage members')
            <a class="nav-link {{ $isActive('admin.members.export') }}" href="{{ route('admin.members.export') }}">
                <i class="fas fa-file-export"></i>
                <span>Exporter</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== GESTION FINANCIÈRE ===== -->
            <div class="nav-label">Finances</div>

            @can('manage finances')
            <a class="nav-link {{ $isActive(['admin.financial.dashboard', 'admin.financial.transactions', 'admin.financial.contributions']) }}" href="{{ route('admin.financial.dashboard') }}">
                <i class="fas fa-coins"></i>
                <span>Tableau de bord</span>
            </a>

            <a class="nav-link {{ $isActive('admin.financial.transactions') }}" href="{{ route('admin.financial.transactions') }}">
                <i class="fas fa-receipt"></i>
                <span>Transactions</span>
            </a>

            <a class="nav-link {{ $isActive('admin.financial.contributions') }}" href="{{ route('admin.financial.contributions') }}">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Cotisations</span>
            </a>

            <a class="nav-link {{ $isActive('admin.financial.report') }}" href="{{ route('admin.financial.report') }}">
                <i class="fas fa-chart-pie"></i>
                <span>Rapports</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== GESTION DES ÉVÉNEMENTS ===== -->
            <div class="nav-label">Événements</div>

            @can('manage events')
            <a class="nav-link {{ $isActive(['admin.events.index', 'admin.events.create', 'admin.events.edit', 'admin.events.show']) }}" href="{{ route('admin.events.index') }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Tous les événements</span>
                <span class="badge bg-primary">{{ \App\Models\Event::upcoming()->count() }}</span>
            </a>

            <a class="nav-link {{ $isActive('admin.events.create') }}" href="{{ route('admin.events.create') }}">
                <i class="fas fa-calendar-plus"></i>
                <span>Créer un événement</span>
            </a>

            <a class="nav-link {{ $isActive('admin.events.calendar') }}" href="{{ route('admin.events.calendar') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Calendrier</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== GALERIE & DOCUMENTS ===== -->
            <div class="nav-label">Médias</div>

            @can('manage gallery')
            <a class="nav-link {{ $isActive(['admin.gallery.index', 'admin.gallery.create', 'admin.gallery.edit']) }}" href="{{ route('admin.gallery.index') }}">
                <i class="fas fa-images"></i>
                <span>Galerie</span>
            </a>
            @endcan

            @can('manage documents')
            <a class="nav-link {{ $isActive(['admin.documents.index', 'admin.documents.create']) }}" href="{{ route('admin.documents.index') }}">
                <i class="fas fa-file-alt"></i>
                <span>Documents</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== CONTENU DU SITE ===== -->
            <div class="nav-label">Contenu du site</div>

            @can('manage pages')
            <a class="nav-link {{ $isActive(['admin.pages.index', 'admin.pages.create', 'admin.pages.edit']) }}" href="{{ route('admin.pages.index') }}">
                <i class="fas fa-file-alt"></i>
                <span>Pages</span>
            </a>
            @endcan

            @can('manage carousels')
            <a class="nav-link {{ $isActive(['admin.carousels.index', 'admin.carousels.create', 'admin.carousels.edit']) }}" href="{{ route('admin.carousels.index') }}">
                <i class="fas fa-sliders-h"></i>
                <span>Carousel</span>
            </a>
            @endcan

            @can('manage testimonials')
            <a class="nav-link {{ $isActive(['admin.testimonials.index', 'admin.testimonials.create', 'admin.testimonials.edit']) }}" href="{{ route('admin.testimonials.index') }}">
                <i class="fas fa-quote-right"></i>
                <span>Témoignages</span>
            </a>
            @endcan

            @can('manage news')
            <a class="nav-link {{ $isActive(['admin.news.index', 'admin.news.create', 'admin.news.edit']) }}" href="{{ route('admin.news.index') }}">
                <i class="fas fa-newspaper"></i>
                <span>Actualités</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== GESTION ===== -->
            <div class="nav-label">Gestion</div>

            @can('manage committee')
            <a class="nav-link {{ $isActive(['admin.committee.index', 'admin.committee.create', 'admin.committee.edit']) }}" href="{{ route('admin.committee.index') }}">
                <i class="fas fa-user-tie"></i>
                <span>Bureau exécutif</span>
            </a>
            @endcan

            @can('view reports')
            <a class="nav-link {{ $isActive(['admin.reports.members', 'admin.reports.financial', 'admin.reports.events']) }}" href="{{ route('admin.reports.members') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Rapports</span>
            </a>
            @endcan

            <!-- ===== DIVIDER ===== -->
            <hr class="sidebar-divider">

            <!-- ===== ESPACE PERSONNEL ===== -->
            <div class="nav-label">Espace personnel</div>

            <a class="nav-link {{ $isActive('my.profile') }}" href="{{ route('my.profile') }}">
                <i class="fas fa-user-circle"></i>
                <span>Mon profil</span>
            </a>

            <a class="nav-link {{ $isActive('my.contributions') }}" href="{{ route('my.contributions') }}">
                <i class="fas fa-hand-holding-heart"></i>
                <span>Mes cotisations</span>
            </a>

            <a class="nav-link {{ $isActive('my.transactions') }}" href="{{ route('my.transactions') }}">
                <i class="fas fa-receipt"></i>
                <span>Mes transactions</span>
            </a>

            <a class="nav-link {{ $isActive('my.events') }}" href="{{ route('my.events') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Mes participations</span>
            </a>

            <!-- ===== PARAMÈTRES ===== -->
            @can('manage settings')
            <hr class="sidebar-divider">
            <div class="nav-label">Système</div>

            <a class="nav-link {{ $isActive('admin.settings') }}" href="{{ route('admin.settings') }}">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
            @endcan
        </nav>

        <!-- ===== USER INFO - Fixe en bas ===== -->
        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-grow-1">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @if(auth()->user()->hasRole('super-admin'))
                            <i class="fas fa-crown me-1" style="color: #ffd700;"></i> Super Admin
                        @elseif(auth()->user()->hasRole('admin'))
                            <i class="fas fa-shield-alt me-1"></i> Administrateur
                        @elseif(auth()->user()->hasRole('president'))
                            Président
                        @elseif(auth()->user()->hasRole('treasurer'))
                            Trésorier
                        @elseif(auth()->user()->hasRole('manager'))
                            Gestionnaire
                        @else
                            Membre
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- ============================================
    MAIN CONTENT
    ============================================ -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">
                    @yield('page-title', 'Tableau de bord')
                    <small>@yield('page-subtitle', '')</small>
                </h1>
            </div>

            <div class="topbar-right">
                <!-- Search -->
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher..." id="globalSearch">
                </div>

                <!-- Notifications -->
                <button class="notification-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge-dot"></span>
                </button>

                <!-- User Dropdown -->
                <button class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="user-info d-none d-md-block">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">
                            @if(auth()->user()->hasRole('super-admin')) Super Admin
                            @elseif(auth()->user()->hasRole('admin')) Administrateur
                            @elseif(auth()->user()->hasRole('president')) Président
                            @else Membre
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-muted ms-1" style="font-size: 0.6rem;"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-2" style="min-width: 220px; border-radius: var(--radius-sm);">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('my.profile') }}">
                            <i class="fas fa-user-circle me-2"></i> Mon profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-th-large me-2"></i> Tableau de bord
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- ============================================
    SCRIPTS
    ============================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ============================================
        // SIDEBAR TOGGLE
        // ============================================
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        // ============================================
        // GLOBAL SEARCH
        // ============================================
        document.getElementById('globalSearch')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query.length > 0) {
                    window.location.href = '{{ route("admin.members.index") }}?search=' + encodeURIComponent(query);
                }
            }
        });

        // ============================================
        // ACTIVE LINK HIGHLIGHT
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.sub-menu.open').forEach(function(menu) {
                menu.style.maxHeight = menu.scrollHeight + 'px';
            });
        });
    </script>

    @stack('scripts')
</body>
</html>