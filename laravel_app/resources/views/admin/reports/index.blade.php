@extends('layouts.app')

@section('title', 'Lapor Dosen - Rekap Izin')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Laporan ke Dosen</h2>
            
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.export', request()->all()) }}"
                class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md shadow-emerald-200 hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Laporan Excel
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Mata Kuliah</label>
                <select name="subject_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm appearance-none">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Rentang Waktu</label>
                <div class="flex items-center gap-2 min-w-0">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 min-w-0 px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm">
                    <span class="text-gray-300 shrink-0">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 min-w-0 px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Jenis Izin</label>
                <select name="type" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm appearance-none">
                    <option value="">Semua Jenis</option>
                    <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-slate-900 transition-all shadow-sm">
                    Filter Laporan
                </button>
                @if(request()->anyFilled(['subject_id', 'start_date', 'end_date', 'type']))
                    <a href="{{ route('admin.reports.index') }}" class="bg-slate-100 text-slate-600 font-semibold py-2 px-3.5 rounded-lg hover:bg-slate-200 transition-all">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3 text-center">Bukti Gambar</th>
                        <th class="px-4 py-3 text-center">Link PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reports as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $item->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold tracking-tight">NIM: {{ $item->user->nim }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold text-slate-600">{{ $item->classRoom->name }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">{{ $item->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                    {{ $item->type === 'sakit' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($item->file && (Str::endsWith($item->file, '.jpg') || Str::endsWith($item->file, '.png') || Str::endsWith($item->file, '.jpeg')))
                                    <button @click="open = true" class="inline-block">
                                        <div class="w-12 h-12 rounded-lg border border-slate-200 overflow-hidden shadow-sm hover:border-blue-400 transition-all">
                                            <img src="{{ asset('storage/' . $item->file) }}" class="w-full h-full object-cover">
                                        </div>
                                    </button>
                                    
                                    <!-- Modal Preview (tetap sama) -->
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-700"
                                        x-transition:enter-start="opacity-0 translate-y-12 scale-95" 
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-300" 
                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-12 scale-95"
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-[2px]"
                                        style="display: none;">
                                        
                                        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative" @click.away="open = false">
                                            <div class="bg-slate-50 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                                                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Bukti Gambar</h3>
                                                <button @click="open = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="p-4 text-center">
                                                <img src="{{ asset('storage/' . $item->file) }}" class="inline-block rounded-xl shadow-inner border border-slate-100 max-h-[500px] object-contain">
                                            </div>
                                            <div class="p-4 bg-slate-50 border-t border-gray-100 flex justify-end">
                                                <a href="{{ asset('storage/' . $item->file) }}" download class="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800">Download Gambar</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-300 font-bold uppercase italic">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($item->file && Str::endsWith($item->file, '.pdf'))
                                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="w-10 h-10 inline-flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-all border border-rose-100">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-300 font-bold uppercase italic">Tidak Ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <i class="fas fa-clipboard-list text-5xl mb-4"></i>
                                    <p class="font-bold">Tidak ada data laporan.</p>
                                    <p class="text-xs">Sesuaikan filter untuk mencari data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="p-6 border-t border-gray-50 bg-slate-50/30">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
@endsection
