<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès interdit - 403</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-container { max-width: 600px; width: 100%; padding: 20px; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .float-animation { animation: float 3s ease-in-out infinite; }
        @keyframes pulse-ring { 0% { transform: scale(0.95); opacity: 0.5; } 50% { transform: scale(1.05); opacity: 0.8; } 100% { transform: scale(0.95); opacity: 0.5; } }
        .pulse-ring { animation: pulse-ring 2s ease-in-out infinite; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="text-center">
            <!-- Illustration -->
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-red-100 rounded-full opacity-20 pulse-ring"></div>
                    <div class="absolute w-48 h-48 bg-orange-100 rounded-full opacity-20 pulse-ring" style="animation-delay: 0.5s;"></div>
                </div>
                <div class="relative">
                    <div class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600 select-none" style="font-size: 140px; line-height: 1;">
                        403
                    </div>
                    <div class="absolute -top-4 -right-4 float-animation">
                        <span class="text-5xl">🚫</span>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Accès interdit</h2>
                <p class="text-gray-500 text-lg max-w-md mx-auto">
                    Vous n'avez pas les autorisations nécessaires pour accéder à cette page.
                </p>
                @if(auth()->check())
                    <div class="mt-4 inline-block p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <p class="text-sm text-gray-700">
                            👤 <strong class="text-blue-600">{{ auth()->user()->name }}</strong>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Rôle(s) : {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Aucun rôle' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if(auth()->check())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Se connecter
                    </a>
                @endif
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-2xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 gap-3 border border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    Accueil
                </a>
            </div>

            <!-- Contact -->
            <div class="mt-10 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    ❓ Si vous pensez qu'il s'agit d'une erreur, 
                    <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">contactez le support</a>
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Fanfare Céleste - La Trompette de Dieu de l'Atlantique
                </p>
            </div>
        </div>
    </div>
</body>
</html>