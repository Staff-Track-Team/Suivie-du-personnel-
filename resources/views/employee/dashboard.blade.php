@extends('layouts.employee')

@section('title', 'Tableau de bord - Mon Espace')

@section('content')
<div class="space-y-6 pb-10">
    <!-- Header Strategy -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                Espace Opérationnel
            </h1>
            <p class="text-sm text-slate-500 font-medium ml-5">Bonjour, {{ auth()->user()->name }}. Suivi de vos objectifs et performances.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white/60 backdrop-blur-xl p-1.5 rounded-2xl border border-white shadow-sm shadow-blue-100/20" x-data="{ time: '' }" x-init="setInterval(() => { const now = new Date(); time = now.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long' }) + ' • ' + now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
            <div class="px-4 py-2 bg-slate-900 rounded-xl">
                <span class="text-xs font-black text-white uppercase tracking-widest" x-text="time"></span>
            </div>
            <div class="pr-4 py-2 hidden sm:flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Connecté</span>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Tasks -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-[2rem] p-6 shadow-xl shadow-blue-200/50 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10 flex flex-col h-full gap-4">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2" />
                    </svg>
                </div>
                <div>
                    <span class="text-white/60 text-[10px] font-black uppercase tracking-widest">Mes Tâches</span>
                    <h4 class="text-4xl font-black text-white tracking-tighter mt-1">{{ $stats['total_tasks'] }}</h4>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-[2rem] p-6 shadow-lg shadow-slate-200/50 border border-white hover:shadow-xl transition-all duration-300 group">
            <div class="flex flex-col h-full gap-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">À faire</span>
                    <h4 class="text-4xl font-black text-slate-900 tracking-tighter mt-1">{{ $stats['pending'] }}</h4>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-[2rem] p-6 shadow-lg shadow-slate-200/50 border border-white hover:shadow-xl transition-all duration-300 group">
            <div class="flex flex-col h-full gap-4">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">En cours</span>
                    <h4 class="text-4xl font-black text-slate-900 tracking-tighter mt-1">{{ $stats['in_progress'] }}</h4>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-slate-900 rounded-[2rem] p-6 shadow-xl shadow-slate-300 group relative overflow-hidden">
            <div class="flex flex-col h-full gap-4 relative z-10">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="w-12 h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500" style="width: {{ $stats['completion_rate'] }}%"></div>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Complétion</span>
                    <h4 class="text-4xl font-black text-white tracking-tighter mt-1">{{ $stats['completion_rate'] }}%</h4>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-blue-600/20 rounded-full blur-3xl"></div>
        </div>
    </div>

    <!-- Main Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Activity -->
        <div class="lg:col-span-8 bg-white/40 backdrop-blur-2xl rounded-[2.5rem] p-8 shadow-xl shadow-blue-100/50 border border-white">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Votre Dynamique</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Nombre de mises à jour sur les 7 derniers jours</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase">Activité personnelle</span>
                </div>
            </div>
            @if(array_sum($activityHistory) > 0)
                <div class="h-[300px] w-full">
                    <canvas id="activityChart"></canvas>
                </div>
            @else
                <div class="h-[300px] w-full flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">Aucune activité récente</p>
                </div>
            @endif
        </div>

        <!-- Task Distribution -->
        <div class="lg:col-span-4 bg-slate-900 rounded-[2.5rem] p-8 shadow-xl shadow-slate-400 border border-slate-800 flex flex-col items-center">
            <h3 class="text-xs font-black text-white uppercase tracking-[0.2em] mb-10">Répartition du travail</h3>
            @if(!empty($tasksByStatus))
                <div class="h-[220px] w-full relative">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-4 w-full">
                    @foreach($tasksByStatus as $status => $count)
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full {{ $status == 'Terminé' ? 'bg-emerald-500' : ($status == 'En cours' ? 'bg-blue-500' : 'bg-slate-400') }}"></div>
                        <span class="text-[10px] font-bold text-slate-100 truncate tracking-tight">{{ $status }} : <span class="text-slate-400">{{ $count }}</span></span>
                    </div>
                    @endforeach
                </div>
            @else
                 <div class="h-[300px] w-full flex flex-col items-center justify-center text-slate-500 border border-dashed border-white/10 rounded-3xl">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 opacity-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <p class="text-[10px] font-black uppercase tracking-widest">Aucune tâche assignée</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Project Contribution -->
        <div class="bg-white/40 backdrop-blur-2xl rounded-[2.5rem] shadow-xl shadow-slate-100 border border-white overflow-hidden p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-slate-900 text-lg flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    Mes Projets
                </h3>
                <a href="{{ route('employee.projects.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Voir mes projets</a>
            </div>
            
            <div class="space-y-6">
                @forelse($recentProjects as $project)
                <div class="group bg-white/60 p-4 rounded-3xl border border-white hover:bg-white hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-blue-100 group-hover:rotate-6 transition-transform">
                                {{ substr($project->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900">{{ $project->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Votre contribution personnelle</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-sm font-black text-slate-900">{{ $project->my_progress }}%</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter italic">Réalisé</span>
                        </div>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $project->my_progress == 100 ? 'bg-gradient-to-r from-emerald-400 to-emerald-600' : 'bg-gradient-to-r from-blue-400 to-blue-600' }} rounded-full" style="width: {{ $project->my_progress }}%"></div>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Aucun projet actif</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Personal Timeline -->
        <div class="bg-slate-900 rounded-[2.5rem] shadow-xl shadow-blue-900/10 overflow-hidden p-8">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-lg font-black text-white flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                    Flux d'activité
                </h3>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Dernières actions</span>
            </div>
            
            <div class="space-y-8 relative before:absolute before:left-[15px] before:top-2 before:bottom-0 before:w-0.5 before:bg-white/10">
                @forelse($recentAudits as $audit)
                <div class="relative pl-10 group">
                    <div class="absolute left-0 top-1.5 w-8 h-8 bg-slate-800 border-4 border-slate-900 rounded-xl z-10 flex items-center justify-center group-hover:scale-110 transition-transform">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-{{ $audit->action === 'created' ? 'emerald' : 'blue' }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $audit->action === 'created' ? 'M12 4v16m8-8H4' : 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z' }}" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">{{ $audit->created_at->diffForHumans() }}</p>
                        <h4 class="text-sm font-bold text-white tracking-tight leading-snug">
                            {{ $audit->user->id === auth()->id() ? 'Vous' : $audit->user->name }}
                            <span class="text-slate-500 font-medium">
                                @if($audit->action === 'status_change')
                                    avez passé la tâche à <span class="text-blue-400 font-bold uppercase text-[10px]">{{ $audit->new_status }}</span>
                                @else
                                    {{ $audit->action === 'created' ? 'avez initié' : 'avez mis à jour' }}
                                @endif
                            </span> 
                        </h4>
                        <div class="mt-1 flex items-center gap-2">
                             <span class="text-xs font-bold text-blue-300 bg-blue-500/10 px-2 py-0.5 rounded-lg border border-blue-500/20 truncate max-w-[200px]">{{ $audit->task->title ?? 'Élément inconnu' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                 <div class="py-12 text-center">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic leading-relaxed">Aucune activité personnelle <br> enregistrée récemment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // Activity Histogram
    @if(array_sum($activityHistory) > 0)
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityGradient = activityCtx.createLinearGradient(0, 0, 0, 300);
    activityGradient.addColorStop(0, 'rgba(37, 99, 235, 0.9)');
    activityGradient.addColorStop(1, 'rgba(37, 99, 235, 0.1)');

    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($activityHistory)),
            datasets: [{
                label: 'Updates',
                data: @json(array_values($activityHistory)),
                backgroundColor: activityGradient,
                borderRadius: 8,
                barThickness: 30,
                hoverBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 10, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    displayColors: false,
                    cornerRadius: 8
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(226, 232, 240, 0.2)', borderDash: [5, 5] }, 
                    ticks: { font: { size: 10, weight: '700' }, precision: 0 } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { font: { size: 10, weight: '700' } } 
                }
            }
        }
    });
    @endif

    // Radial Task Status
    @if(!empty($tasksByStatus))
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($tasksByStatus)),
            datasets: [{
                data: @json(array_values($tasksByStatus)),
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
                hoverOffset: 20,
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    @endif
</script>
@endsection
