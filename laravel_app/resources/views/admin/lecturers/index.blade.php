@extends('layouts.app')

@section('title', 'Kontak Dosen')

@section('content')
    <div
        x-data="{ 
            showCreateModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
            showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }}, 
            currentLecturer: @js(old('id') ? \App\Models\Lecturer::find(old('id')) : (object)[])
        }">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kontak Dosen</h2>
                <p class="text-slate-500 text-sm">Manajemen kontak dosen untuk integrasi WhatsApp mahasiswa.</p>
            </div>
            <button @click="showCreateModal = true" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Dosen
            </button>
        </div>

        <!-- Lecturers Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                        <tr>
                            <th class="px-4 py-3">Nama Dosen</th>
                            <th class="px-4 py-3">No. WhatsApp</th>
                            <th class="px-4 py-3">Mata Kuliah / Info</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($lecturers as $lecturer)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($lecturer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $lecturer->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-mono text-slate-600">
                                    {{ $lecturer->phone }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $lecturer->subject ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="currentLecturer = @js($lecturer); showEditModal = true"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <form id="delete-lecturer-{{ $lecturer->id }}" action="{{ route('admin.lecturers.destroy', $lecturer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-lecturer-{{ $lecturer->id }}', 'Kontak dosen {{ addslashes($lecturer->name) }} akan dihapus secara permanen!')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fas fa-address-book text-6xl mb-4"></i>
                                        <p class="text-lg font-bold">Belum ada kontak dosen</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($lecturers->hasPages())
                <div class="p-6 border-t border-gray-50 bg-slate-50/30">
                    {{ $lecturers->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah -->
        <div x-cloak :class="showCreateModal ? 'pointer-events-auto' : 'pointer-events-none'" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="showCreateModal = false" class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out" :class="showCreateModal ? 'opacity-100' : 'opacity-0'"></div>
            <div @click.stop :aria-hidden="(!showCreateModal).toString()" :inert="!showCreateModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out" :class="showCreateModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Dosen</h3>
                    <button @click="showCreateModal = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.lecturers.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">Nama Dosen</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium" placeholder="Contoh: Budi Santoso, M.Kom">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium" placeholder="Contoh: 628123456789">
                        <p class="text-[10px] text-slate-400 mt-1 ml-1">Gunakan format internasional tanpa + (awalan 62).</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">Mata Kuliah / Keterangan</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium" placeholder="(Opsional)">
                    </div>
                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="showCreateModal = false" class="flex-1 bg-slate-100 text-slate-600 font-bold py-2.5 rounded-lg hover:bg-slate-200 transition-all text-sm">Batal</button>
                        <button type="submit" class="flex-[2] bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-cloak :class="showEditModal ? 'pointer-events-auto' : 'pointer-events-none'" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="showEditModal = false" class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out" :class="showEditModal ? 'opacity-100' : 'opacity-0'"></div>
            <div @click.stop :aria-hidden="(!showEditModal).toString()" :inert="!showEditModal" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out" :class="showEditModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Edit Dosen</h3>
                    <button @click="showEditModal = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form :action="'{{ url('admin/lecturers') }}/' + currentLecturer.id" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="currentLecturer.id">
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">Nama Dosen</label>
                        <input type="text" name="name" :value="currentLecturer.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="phone" :value="currentLecturer.phone" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium">
                        <p class="text-[10px] text-slate-400 mt-1 ml-1">Gunakan format internasional tanpa + (awalan 62).</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-1.5">Mata Kuliah / Keterangan</label>
                        <input type="text" name="subject" :value="currentLecturer.subject" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium">
                    </div>
                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="showEditModal = false" class="flex-1 bg-slate-100 text-slate-600 font-bold py-2.5 rounded-lg hover:bg-slate-200 transition-all text-sm">Batal</button>
                        <button type="submit" class="flex-[2] bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
