@props([
    'id', 
    'title' => 'Confirmation de suppression', 
    'message' => 'Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.',
    'confirmText' => 'Supprimer',
    'cancelText' => 'Annuler',
    'type' => 'danger'
])

<template x-teleport="body">
    <div 
        x-show="{{ $id }}Open" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0"
        x-cloak
    >
        <!-- Overlay -->
        <div 
            x-show="{{ $id }}Open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="{{ $id }}Open = false"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        ></div>

        <!-- Modal Content -->
        <div 
            x-show="{{ $id }}Open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden p-8 z-50 text-left transition-all"
            @click.stop
        >
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl {{ $type === 'danger' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }} mb-6">
                    @if($type === 'danger')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @endif
                </div>
                
                <h3 class="text-xl font-black text-slate-900 mb-2 tracking-tight">{{ $title }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">{{ $message }}</p>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <button 
                    type="button" 
                    @click="{{ $id }}Open = false" 
                    class="flex-1 px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all text-sm"
                >
                    {{ $cancelText }}
                </button>
                <button 
                    type="button" 
                    @click="document.getElementById('form-' + '{{ $id }}')?.submit()" 
                    class="flex-1 px-6 py-3 rounded-2xl {{ $type === 'danger' ? 'bg-red-600 hover:bg-red-700 shadow-red-500/20' : 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/20' }} text-white font-bold transition-all shadow-lg text-sm"
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>
