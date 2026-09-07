@extends('layouts.app')

@section('title', 'Manajemen Izin')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manajemen Izin</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.permissions.export') }}"
                class="bg-emerald-600 text-white px-3.5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <button onclick="window.print()"
                class="bg-white border border-gray-200 text-gray-600 px-3.5 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Rekap
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6">
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari Nama</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama mahasiswa..."
                        class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-transparent rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300"></i>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jenis Izin</label>
                <select name="type"
                    class="w-full px-3 py-2 bg-slate-50 border border-transparent rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none">
                    <option value="">Semua Jenis</option>
                    <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="submit"
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold py-2 rounded-lg transition-all">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead
                    class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                    <tr>
                        <th class="px-4 py-3">Mahasiswa</th>
                        <th class="px-4 py-3">Mata Kuliah</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($permissions as $item)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $item->user->name }}</p>
                                        <p class="text-xs text-gray-500">NIM: {{ $item->user->nim ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-medium text-gray-600">{{ $item->classRoom->name }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="capitalize px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $item->type === 'sakit' ? 'bg-red-100 text-red-600' : '' }}
                                    {{ $item->type === 'izin' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                    {{ $item->type === 'alpha' ? 'bg-purple-100 text-purple-600' : '' }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2" x-data="{ open: false }">
                                    <button @click="open = true"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <div x-cloak :class="open ? 'pointer-events-auto' : 'pointer-events-none'"
                                        class="fixed inset-0 z-[100] flex items-center justify-center p-4">

                                        <div @click="open = false"
                                            class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                                            :class="open ? 'opacity-100' : 'opacity-0'"></div>

                                        <div @click.stop :aria-hidden="(!open).toString()" :inert="!open"
                                            class="bg-white w-full max-w-lg max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                                            :class="open ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                                            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-800">Detail Laporan Izin</h3>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Informasi lengkap laporan mahasiswa</p>
                                                </div>
                                                <button @click="open = false" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                                                    <i class="fas fa-times text-md"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-5 flex-1 overflow-y-auto">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Mahasiswa</p>
                                                        <p class="px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg font-bold text-gray-800 text-sm">{{ $item->user->name }}</p>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Mata Kuliah</p>
                                                        <p class="px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg font-bold text-gray-800 text-sm">{{ $item->classRoom->name }}</p>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Jenis Izin</p>
                                                        <p class="px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg font-bold text-gray-800 text-sm capitalize">{{ $item->type }}</p>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Tanggal</p>
                                                        <p class="px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="space-y-1">
                                                    <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Alasan</p>
                                                    <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-gray-600 text-sm italic">
                                                        "{{ $item->description }}"
                                                    </div>
                                                </div>
                                                @if($item->file)
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Bukti Pendukung</p>
                                                        @if(Str::endsWith($item->file, '.pdf'))
                                                            <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                                                class="flex items-center gap-3 p-3 bg-red-50 text-red-700 rounded-xl border border-red-100 hover:bg-red-100 transition-colors">
                                                                <i class="fas fa-file-pdf text-xl"></i>
                                                                <span class="font-bold text-xs">Lihat Dokumen PDF</span>
                                                            </a>
                                                        @else
                                                            <div class="w-full bg-slate-50 border border-slate-100 rounded-xl p-2 flex items-center justify-center">
                                                                <img src="{{ asset('storage/' . $item->file) }}" alt="Bukti pendukung"
                                                                    class="max-w-full max-h-[45vh] w-auto h-auto object-contain rounded-lg"
                                                                    loading="lazy">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-4 bg-slate-50 border-t border-gray-100 flex justify-end">
                                                <button @click="open = false"
                                                    class="px-8 py-2 bg-slate-200 text-slate-600 font-bold rounded-lg hover:bg-slate-300 transition-colors text-xs uppercase tracking-widest">Tutup</button>
                                            </div>
                                        </div>
                                    </div>

                                    <form id="delete-permission-{{ $item->id }}" action="{{ route('admin.permissions.destroy', $item) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-permission-{{ $item->id }}', 'Data izin {{ $item->user->name }} ini akan dihapus secara permanen!')"
                                            class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-search text-gray-200 text-5xl mb-4"></i>
                                    <p class="text-gray-400 italic">Data tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $permissions->links() }}
        </div>
    </div>
@endsection