@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight text-center md:text-left">Dashboard Admin</h2>
            <p class="text-slate-500 font-medium text-center md:text-left">Selamat datang kembali di sistem manajemen {{ \App\Models\Setting::get('class_code', '07TPLE018') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('admin.permissions.index') }}"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md shadow-blue-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-list"></i> Lihat Semua Data
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-5 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Total Izin</h3>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-heartbeat"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Sakit</h3>
            <p class="text-xl font-bold text-gray-900">{{ $stats['sakit'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Izin</h3>
            <p class="text-xl font-bold text-gray-900">{{ $stats['izin'] }}</p>
        </div>


        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm ring-2 ring-blue-500/10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Mahasiswa</h3>
            <p class="text-xl font-bold text-gray-900">{{ $stats['students'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm ring-2 ring-emerald-500/10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-building-columns"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Mata Kuliah</h3>
            <p class="text-xl font-bold text-gray-900">{{ $stats['subjects'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-blue-600"></i> Distribusi Jenis Izin
            </h3>
            <div class="h-64 relative">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-clock text-blue-600"></i> Pengajuan Terbaru
            </h3>
            <div class="space-y-5">
                @forelse($recentPermissions as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->type }} - {{ $item->created_at->diffForHumans() }}</p>
                            <div class="mt-1">
                                @if($item->status === 'pending')
                                    <span
                                        class="text-[10px] px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-bold uppercase tracking-wider">Pending</span>
                                @elseif($item->status === 'approved')
                                    <span
                                        class="text-[10px] px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-bold uppercase tracking-wider">Approved</span>
                                @else
                                    <span
                                        class="text-[10px] px-2 py-0.5 bg-red-100 text-red-700 rounded-full font-bold uppercase tracking-wider">Rejected</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-8 italic">Belum ada pengajuan</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('typeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sakit', 'Izin'],
                datasets: [{
                    data: [{{ $stats['sakit'] }}, {{ $stats['izin'] }}],
                    backgroundColor: ['#ef4444', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: 'Inter', size: 12 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
@endpush