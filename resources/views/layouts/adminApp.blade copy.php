<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background: #f6f7fb; }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #283593, #3946B2);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
        }
        .sidebar a {
            color: rgba(255,255,255,.9);
            text-decoration: none;
        }
        .sidebar a:hover { color: #fff; }
        .sidebar .nav-link {
            padding: .75rem 1.25rem;
            border-radius: .6rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.15);
        }
        .content {
            margin-left: 260px;
            padding: 2rem 1.5rem;
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            border: 1px solid rgba(0,0,0,.06);
            border-radius: .75rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>

    @stack('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="p-4 pb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-music"></i>
                <div class="fw-bold fs-5">Fanfare Céleste</div>
            </div>
            <div class="mt-2 text-white-50" style="font-size:.9rem;">Espace membre</div>
        </div>

        <nav class="p-2">
            @php
                $routeName = request()->route()->getName();
            @endphp

            <a class="nav-link {{ $routeName === 'my.profile' ? 'active' : '' }}" href="{{ route('my.profile') }}">
                <i class="fas fa-user"></i>
                <span>Mon profil</span>
            </a>

            <a class="nav-link {{ $routeName === 'my.contributions' ? 'active' : '' }}" href="{{ route('my.contributions') }}">
                <i class="fas fa-coins"></i>
                <span>Mes cotisations</span>
            </a>

            <a class="nav-link {{ $routeName === 'my.transactions' ? 'active' : '' }}" href="{{ route('my.transactions') }}">
                <i class="fas fa-receipt"></i>
                <span>Mes transactions</span>
            </a>

            <a class="nav-link {{ $routeName === 'my.events' ? 'active' : '' }}" href="{{ route('my.events') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Mes participations</span>
            </a>
        </nav>

        <div class="p-3 border-top" style="border-color: rgba(255,255,255,.15) !important;">
            <div class="small text-white-50">Connecté en tant que</div>
            <div class="fw-semibold">{{ auth()->user()->name }}</div>
        </div>

        <div class="p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light w-100">Déconnexion</button>
            </form>
        </div>
    </aside>

    <div class="content">
        <div class="topbar mb-4">
            <div>
                @yield('page-title')
            </div>
            <div class="text-end text-muted">
                <i class="fas fa-shield-alt"></i> Sécurisé
            </div>
        </div>

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>

