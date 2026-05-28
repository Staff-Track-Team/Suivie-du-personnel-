<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérification Email - CFP-CMD</title>
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
    <style>body { font-family: 'Outfit', sans-serif; } [x-cloak] { display: none !important; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900 p-4">
    <!-- Unified Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/auth_bg.jpeg') }}" class="w-full h-full object-cover opacity-40 scale-105" alt="Collaboration Background">
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/60 via-slate-900/40 to-transparent"></div>
    </div>

    <div class="w-full max-w-md bg-white/70 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/40 relative z-10">
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white mb-4 shadow-lg shadow-blue-500/10 p-2 mx-auto">
                    <img src="{{ asset('logo/logo.png') }}" class="w-full h-full object-contain" alt="Logo CFP-CMD">
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Vérification Email</h1>
                <p class="text-slate-500 mt-2">Sécurisation du processus de réinitialisation.</p>
            </div>

            <form action="{{ route('password.verify-otp.submit') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ request('email') }}">
                
                <div>
                    <label for="otp" class="block text-sm font-medium text-slate-700 mb-1">Code de vérification</label>
                    <input type="text" name="otp" id="otp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors text-center text-2xl tracking-widest font-bold" placeholder="XXXXXX" required autofocus maxlength="6">
                    @error('otp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                    Valider le code
                </button>
            </form>
            <div class="mt-6 flex justify-between gap-4">
                <form action="{{ route('password.email') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <button type="submit" class="text-sm text-blue-500 hover:text-blue-700 font-bold underline">Renvoyer le code</button>
                </form>

                <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-blue-600 font-medium">Retour connexion</a>
            </div>
        </div>
    </div>
    
    <x-toast />
</body>
</html>
