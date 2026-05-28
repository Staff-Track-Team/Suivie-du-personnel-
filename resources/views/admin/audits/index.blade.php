@extends('layouts.admin')

@section('title', 'Audit & Historique')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Journal d'Audit</h2>
            <p class="text-slate-500">Suivi complet de toutes les activités sur les tâches.</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('admin.audits.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Projet</label>
                <select name="project_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">Tous les projets</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tâche (Titre)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une tâche..." class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-colors font-bold text-sm">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date & Heure</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Utilisateur</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Activité</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Tâche</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Changement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4">
                            <div class="text-sm font-medium text-slate-900">{{ $audit->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $audit->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                    {{ substr($audit->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div class="text-sm font-semibold text-slate-700">{{ $audit->user->name ?? 'Système' }}</div>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($audit->action == 'created')
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">CRÉATION</span>
                            @elseif($audit->action == 'updated')
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">MODIFICATION</span>
                            @elseif($audit->action == 'status_change')
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">STATUT</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">{{ $audit->action }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="text-sm font-medium text-slate-800">{{ $audit->task->title ?? 'N/A' }}</div>
                            <div class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest">{{ $audit->task->project->name ?? 'Sans Projet' }}</div>
                        </td>
                        <td class="p-4">
                            @if($audit->old_status && $audit->new_status)
                                <div class="text-[11px] text-slate-600">
                                    <span class="line-through text-slate-400">{{ $audit->old_status }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    <span class="font-bold text-blue-600">{{ $audit->new_status }}</span>
                                </div>
                            @elseif($audit->formatted_details)
                                <div class="text-[11px] text-slate-500 leading-relaxed">{{ $audit->formatted_details }}</div>
                            @else
                                <span class="text-[11px] text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 italic">Aucun enregistrement trouvé d'après vos critères.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $audits->links() }}
        </div>
    </div>
</div>
@endsection
