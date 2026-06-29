<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trop de requêtes - 429</title>
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
                    <div class="w-64 h-64 bg-purple-100 rounded-full opacity-20 pulse-ring"></div>
                    <div class="absolute w-48 h-48 bg-pink-100 rounded-full opacity-20 pulse-ring" style="animation-delay: 0.5s;"></div>
                </div>
                <div class="relative">
                    <div class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 select-none" style="font-size: 140px; line-height: 1;">
                        429
                    </div>
                    <div class="absolute -top-4 -right-4 float-animation">
                        <span class="text-5xl">🐢</span>
                    </div>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Trop de requêtes</h2>
                <p class="text-gray-500 text-lg max-w-md mx-auto">
                    Vous avez envoyé trop de requêtes en peu de temps. Prenez une pause et réessayez dans un instant.
                </p>
                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-full border border-purple-200">
                    <span class="text-purple-500">⏳</span>
                    <span class="text-sm text-purple-700">Veuillez patienter quelques minutes</span>
                </div>
            </div>

            <!-- Timer -->
            <div class="mb-8 p-4 bg-white rounded-2xl shadow-md max-w-xs mx-auto">
                <div class="flex items-center justify-center gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600" id="countdown">60</div>
                        <div class="text-xs text-gray-500">secondes</div>
                    </div>
                    <div class="text-2xl text-gray-300">⏱️</div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-600">5</div>
                        <div class="text-xs text-gray-500">tentatives</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="location.reload()" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Réessayer
                </button>
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
                    💡 Si le problème persiste, 
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

    <script>
        // Compte à rebours
        let seconds = 60;
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            const interval = setInterval(() => {
                seconds--;
                countdownElement.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                    countdownElement.textContent = '🔄';
                    countdownElement.classList.add('text-green-600');
                }
            }, 1000);
        }
    </script>
</body>
</html>