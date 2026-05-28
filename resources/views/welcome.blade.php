<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CFP-CMD - Gestion des Tâches</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img src="{{ asset('logo/logo.png') }}" class="w-10 h-10 object-contain" alt="Logo CFP-CMD">
                    <span class="font-bold text-xl tracking-tight text-slate-900">CFP-CMD<span class="text-blue-600">Tasks</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-medium transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-slate-900 text-white font-medium hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Espace Collaborateur
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Blobs -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[600px] h-[600px] bg-blue-100/50 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[500px] h-[500px] bg-indigo-100/50 rounded-full blur-3xl opacity-60"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold mb-6 border border-blue-100">
                Plateforme de Gestion Unifiée
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-tight">
                Gérez vos projets avec <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Excellence & Fluidité</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-500 mb-10">
                L'outil centralisé pour les équipes de CFP-CMD. Suivez l'avancement, collaborez en temps réel et atteignez vos objectifs plus rapidement.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-2xl bg-blue-600 text-white text-lg font-semibold shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1">
                        Accéder à mon espace
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-blue-600 text-white text-lg font-semibold shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        Se connecter
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endauth
            </div>

            <!-- Stats or Feature Highlights -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 text-left max-w-5xl mx-auto">
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Suivi de Tâches</h3>
                    <p class="text-slate-500">Assignez, suivez et validez les tâches en temps réel pour une productivité maximale.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Gestion Projets</h3>
                    <p class="text-slate-500">Vue d'ensemble sur tous les projets en cours. Respectez les délais et les budgets.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Audit & Traçabilité</h3>
                    <p class="text-slate-500">Historique complet des actions pour une transparence totale au sein de l'entreprise.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-slate-400 text-sm">
            <p>&copy; 2026 CFP-CMD. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>