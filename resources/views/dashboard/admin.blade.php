<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                {{ __('Overview') }}
            </h2>
            <div class="flex items-center mt-2">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.3em]">LMS Analytics Dashboard</p>
            </div>
        </div>
    </x-slot>

    <style>
        .compact-card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            border: 1px solid #F1F5F9;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.01), 0 1px 2px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .compact-card:hover {
            border-color: #E2E8F0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            transform: translateY(-2px);
        }
        .icon-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .section-header {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: #94A3B8;
            margin-bottom: 1.5rem;
            display: block;
        }
    </style>

    <div class="space-y-12">
        <!-- STATS GRID -->
        <div>
            <span class="section-header">Key Metrics</span>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">

                <!-- SISWA -->
                <div class="compact-card group">
                    <div class="icon-circle bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Siswa Terdaftar</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-2 tracking-tighter">{{ number_format($totalSiswa) }}</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-lg w-max">
                        Active
                    </div>
                </div>

                <!-- GURU -->
                <div class="compact-card group">
                    <div class="icon-circle bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Guru</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-2 tracking-tighter">{{ number_format($totalGuru) }}</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-lg w-max">
                        Verified
                    </div>
                </div>

                <!-- KELAS -->
                <div class="compact-card group">
                    <div class="icon-circle bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Kelas</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-2 tracking-tighter">{{ number_format($totalKelas) }}</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-lg w-max">
                        Live
                    </div>
                </div>
            </div>
        </div>

            <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- MAIN CHART -->
            <div class="lg:col-span-8">
                <span class="section-header">Class Engagement</span>
                <div class="compact-card">
                    <div class="h-[350px]">
                        <canvas id="kelasActivityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- DOUGHNUTS -->
            <div class="lg:col-span-4 space-y-8">

                <!-- LMS Activity (7 hari terakhir) -->
                <div>
                    <span class="section-header">Aktivitas LMS (7 hari)</span>
                    <div class="compact-card" style="padding: 1.25rem;">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">Guru</span>
                                    <span class="text-[9px] font-bold text-emerald-500 uppercase">Total tindakan</span>
                                </div>
                                <div class="h-28">
                                    <canvas id="guruLmsActivity7d"></canvas>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">Siswa</span>
                                    <span class="text-[9px] font-bold text-blue-500 uppercase">Total aktivitas</span>
                                </div>
                                <div class="h-28">
                                    <canvas id="siswaLmsActivity7d"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="section-header">Guru Readiness</span>
                    <div class="compact-card flex items-center justify-between">
                        <div class="h-28 w-28 relative flex items-center justify-center">
                            <canvas id="guruProgressChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="text-sm font-black text-gray-900 tracking-tighter">{{ round(($readyMapels / max(1, ($readyMapels + $inProgressMapels))) * 100) }}%</span>
                            </div>
                        </div>
                        <div class="flex-1 ml-6 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-gray-400 uppercase">Siap</span>
                                <span class="text-[10px] font-black text-emerald-500">{{ $readyMapels }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-gray-400 uppercase">Proses</span>
                                <span class="text-[10px] font-black text-indigo-500">{{ $inProgressMapels }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="section-header">Siswa Engagement</span>
                    <div class="compact-card flex items-center justify-between">
                        <div class="h-28 w-28 relative flex items-center justify-center">
                            <canvas id="siswaActivityChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="text-sm font-black text-gray-900 tracking-tighter">{{ round(($activeStudents / max(1, $totalSiswa)) * 100) }}%</span>
                            </div>
                        </div>
                        <div class="flex-1 ml-6 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-gray-400 uppercase">Aktif</span>
                                <span class="text-[10px] font-black text-blue-500">{{ $activeStudents }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-gray-400 uppercase">Pasif</span>
                                <span class="text-[10px] font-black text-gray-300">{{ $inactiveStudents }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // NOTE: Chart.js scripts must be valid JS.
        document.addEventListener('DOMContentLoaded', function() {

            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94A3B8';

            const doughnutOptions = {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '82%',
                plugins: { legend: { display: false } },
                animation: { animateScale: true }
            };

            // GURU CHART COLOR LOGIC
            const guruPercent = {{ round(($readyMapels / max(1, ($readyMapels + $inProgressMapels))) * 100) }};
            const guruColor = guruPercent >= 75 ? '#10B981' : (guruPercent >= 40 ? '#F59E0B' : '#F43F5E');

            new Chart(document.getElementById('guruProgressChart'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ $readyMapels }}, {{ $inProgressMapels }}],
                        backgroundColor: [guruColor, '#F1F5F9'],
                        borderWidth: 0,
                        borderRadius: 6
                    }]
                },
                options: doughnutOptions
            });

            // SISWA CHART COLOR LOGIC
            const siswaPercent = {{ round(($activeStudents / max(1, $totalSiswa)) * 100) }};
            const siswaColor = siswaPercent >= 75 ? '#3B82F6' : (siswaPercent >= 40 ? '#F59E0B' : '#F43F5E');

            new Chart(document.getElementById('siswaActivityChart'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ $activeStudents }}, {{ $inactiveStudents }}],
                        backgroundColor: [siswaColor, '#F1F5F9'],
                        borderWidth: 0,
                        borderRadius: 6
                    }]
                },
                options: doughnutOptions
            });

            new Chart(document.getElementById('kelasActivityChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($kelasLabels) !!},
                    datasets: [{
                        data: {!! json_encode($kelasActiveCounts) !!},
                        backgroundColor: '#6366F1',
                        borderRadius: 8,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false }, ticks: { stepSize: 1, font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // LMS Activity (last 7 days)
            const guruEl = document.getElementById('guruLmsActivity7d');
            const siswaEl = document.getElementById('siswaLmsActivity7d');

            console.log('[Dashboard admin] guruEl', guruEl);
            console.log('[Dashboard admin] siswaEl', siswaEl);
            console.log('[Dashboard admin] labels7d', {!! json_encode($labels7d ?? []) !!});
            console.log('[Dashboard admin] guruActiveCountsByDay', {!! json_encode($guruActiveCountsByDay ?? []) !!});
            console.log('[Dashboard admin] siswaActiveCountsByDay', {!! json_encode($siswaActiveCountsByDay ?? []) !!});

            if (guruEl) {
                new Chart(guruEl, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels7d ?? []) !!},
                        datasets: [{
                            label: 'Aktivitas Guru',
                            data: {!! json_encode($guruActiveCountsByDay ?? []) !!},
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false }, ticks: { font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            if (siswaEl) {
                new Chart(siswaEl, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels7d ?? []) !!},
                        datasets: [{
                            label: 'Aktivitas Siswa',
                            data: {!! json_encode($siswaActiveCountsByDay ?? []) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            borderWidth: 2,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false }, ticks: { font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
