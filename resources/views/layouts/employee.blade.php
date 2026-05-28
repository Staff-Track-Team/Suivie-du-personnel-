<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Employé - CFP-CMD</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Header -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 lg:hidden fixed w-full z-20 top-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="font-bold text-lg text-slate-900">CFP-CMD<span class="text-blue-600">Employee</span></span>
            </div>
            @if(auth()->user()->profil)
                <img src="{{ asset(auth()->user()->profil) }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
            @else
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
        </header>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-emerald-100 to-emerald-50 text-emerald-900 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto shadow-2xl flex flex-col h-full shrink-0 lg:pt-0">
            <div class="h-auto py-6 flex flex-col justify-center px-6 border-b border-emerald-200 bg-white/50 backdrop-blur-sm flex">
                <div class="flex items-center">
                    <img src="{{ asset('logo/logo.png') }}" class="w-10 h-10 object-contain mr-3" alt="Logo CFP-CMD">
                    <span class="font-bold text-xl tracking-tight text-emerald-900">CFP-CMD<span class="text-emerald-600">Employee</span></span>
                </div>
                <p class="text-[10px] text-emerald-600 mt-1 uppercase font-semibold tracking-wider">Plateforme de gestion centralisée</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('hr.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors {{ request()->routeIs('hr.dashboard') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Mes Projets -->
                <a href="{{ route('employee.projects.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('employee.projects.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors {{ request()->routeIs('employee.projects.*') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Mes Projets</span>
                </a>

                <!-- Mes Tâches -->
                <a href="{{ route('employee.tasks.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('employee.tasks.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-200' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors {{ request()->routeIs('employee.tasks.*') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="font-medium">Mes Tâches</span>
                </a>

            </nav>

            <!-- Bottom Section: Profile & Logout -->
            <div class="p-4 border-t border-emerald-200 bg-white/50 backdrop-blur-sm z-30">
                <p class="px-2 text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-2">Compte</p>
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group {{ request()->routeIs('profile*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-200' }}">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors {{ request()->routeIs('profile.*') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-800' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Profil</span>
                </a>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all group text-emerald-700 hover:text-emerald-900 hover:bg-emerald-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors text-emerald-600 group-hover:text-emerald-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium">Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content -->
        <main class="flex-1 min-w-0 flex flex-col h-full overflow-hidden transition-all duration-300 pt-16 lg:pt-0">
            <!-- Topbar Desktop -->
            <div class="hidden lg:flex items-center justify-between h-16 bg-white border-b border-slate-200 px-8 sticky top-0 z-20">
                <h1 class="text-xl font-bold text-slate-800">@yield('title', 'Mon Espace')</h1>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden xl:block">
                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Collaborateur</p>
                        </div>
                        @if(auth()->user()->profil)
                            <img src="{{ asset(auth()->user()->profil) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-lg shadow-slate-500/20">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-500/20">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-4 md:p-8 flex-1 overflow-y-auto bg-slate-100">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('modal')

    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-slate-900/50 backdrop-blur-sm lg:hidden" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"></div>

    <x-toast />
</body>
</html>
