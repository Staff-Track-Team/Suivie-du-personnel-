<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Système RH CFP-CMD')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('logo/logo.png') }}" class="h-8 w-auto" alt="Logo CFP-CMD">
                        <span class="ml-3 text-xl font-bold text-gray-800">Système RH</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User Menu -->
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Ouvrir le menu utilisateur</span>
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-white font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="userMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">Rôle: {{ auth()->user()->role }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon Profil</a>
                                <form action="{{ route('auth.logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">© 2026 CFP-CMD. Système RH - Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });

        // Flash messages
        @if(session('success'))
            setTimeout(() => {
                alert('{{ session('success') }}');
            }, 500);
        @endif

        @if(session('error'))
            setTimeout(() => {
                alert('{{ session('error') }}');
            }, 500);
        @endif
    </script>
</body>
</html>
