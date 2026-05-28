<!-- Sidebar Component -->
<aside class="w-64 bg-gradient-to-b from-emerald-100 to-emerald-50 shadow-lg border-r border-emerald-200 min-h-screen fixed left-0 top-0 z-20">
    <!-- Sidebar Header -->
    <div class="p-6 border-b border-emerald-200 bg-white/50 backdrop-blur-sm">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('logo/logo.png') }}" class="w-10 h-10 rounded-lg" alt="Logo CFP-CMD">
            <div>
                <h2 class="text-lg font-bold text-emerald-900">Système RH</h2>
                <p class="text-xs text-emerald-600">{{ auth()->user()->role_label }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('hr.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('hr.dashboard') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <!-- Gestion des Employés (Admin/Manager RH) -->
            @if(auth()->user()->canManageRH())
            <li>
                <a href="{{ route('hr.employees.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('hr.employees.*') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Employés</span>
                </a>
            </li>
            @endif

            <!-- Gestion des Présences (Admin/Manager RH) -->
            @if(auth()->user()->canManageRH())
            <li>
                <a href="{{ route('hr.attendance.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('hr.attendance.*') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Présences</span>
                </a>
            </li>
            @endif

            <!-- Gestion des Congés -->
            <li>
                <a href="{{ route('hr.leaves.my') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('hr.leaves.my') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Mes Congés</span>
                </a>
            </li>

            <!-- Validation des Congés (Manager RH) -->
            @if(auth()->user()->canManageRH())
            <li>
                <a href="{{ route('hr.leaves.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('hr.leaves.index') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Validation Congés</span>
                </a>
            </li>
            @endif

            <!-- Profil -->
            <li>
                <a href="{{ route('profile') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-emerald-700 hover:bg-emerald-200 hover:text-emerald-900 transition-colors {{ request()->routeIs('profile*') ? 'bg-emerald-200 text-emerald-900' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Mon Profil</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-emerald-200 bg-white/50 backdrop-blur-sm">
        <form action="{{ route('auth.logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
