<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Camtel Gestion Tâches</title>
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
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">
    <!-- Unified Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/auth_bg.jpeg') }}" class="w-full h-full object-cover opacity-40 scale-105" alt="Collaboration Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/60 via-slate-900/40 to-transparent"></div>
    </div>

    <div class="w-full max-w-md bg-white/70 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/40 relative z-10 transition-all duration-500 hover:shadow-blue-500/20">
        <div class="p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white mb-4 shadow-lg shadow-blue-500/10 p-2">
                    <img src="{{ asset('logo/logo.png') }}" class="w-full h-full object-contain" alt="Logo Camtel">
                </div>
                <h1 class="text-xl font-bold text-slate-800">Mot de passe oublié</h1>
                <p class="text-slate-500 mt-2 text-sm">Récupération d'accès - Plateforme Camtel</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-slate-700">Email professionnel</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" 
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-slate-700 placeholder-slate-400" 
                        placeholder="nom@camtel.cm" value="{{ old('email') }}" required autofocus>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform active:scale-[0.98]">
                    Envoyer le code
                </button>
            </form>
        </div>
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la connexion
            </a>
        </div>
    </div>
    <x-toast />
</body>
</html>
