@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa')

@section('content')
    <div
        x-data="{ 
            showAddModal: {{ $errors->any() && !session('import_error') ? 'true' : 'false' }}, 
            showImportModal: false, 
            editMode: false, 
            currentStudent: {},
            selectedSubjects: []
        }" x-init="$watch('currentStudent', value => { 
            if(value.subjects) {
                selectedSubjects = value.subjects.map(s => s.id);
            } else {
                selectedSubjects = [];
            }
        })">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Mahasiswa</h2>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showImportModal = true"
                    class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 px-3.5 py-2 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 border border-emerald-100">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <button @click="editMode = false; currentStudent = {}; showAddModal = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md shadow-blue-200 transition-all flex items-center gap-2 group">
                    <i class="fas fa-plus transition-transform group-hover:rotate-90"></i> Tambah Mahasiswa
                </button>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6">
            <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[250px] space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cari Mahasiswa</label>
                    <div class="relative group">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama atau NIM..."
                            class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm">
                    </div>
                </div>
                <div class="flex-1 min-w-[200px] space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Mata Kuliah</label>
                    <select name="subject_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm appearance-none">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-slate-900 transition-all shadow-sm">
                    Filter Data
                </button>
                @if(request()->anyFilled(['search']))
                    <a href="{{ route('admin.students.index') }}"
                        class="bg-slate-100 text-slate-600 px-3.5 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead
                        class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                        <tr>
                            <th class="px-4 py-3">Nama Mahasiswa</th>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3 text-center">Mata Kuliah</th>
                            <th class="px-4 py-3">Prodi</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <p class="font-bold text-slate-800">{{ $student->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-bold">{{ $student->nim }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center">
                                        @if(request('subject_id'))
                                            @php
                                                $selectedSubject = $subjects->firstWhere('id', request('subject_id'));
                                            @endphp
                                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                                {{ $selectedSubject->name ?? 'Mata Kuliah' }}
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                                                Semua
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-slate-600 font-medium">{{ $student->prodi ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="editMode = true; currentStudent = @js($student); showAddModal = true"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <form id="delete-student-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-student-{{ $student->id }}', 'Data mahasiswa {{ $student->name }} akan dihapus secara permanen!')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fas fa-user-graduate text-6xl mb-4"></i>
                                        <p class="text-lg font-bold">Belum ada data mahasiswa</p>
                                        <p class="text-sm">Klik 'Tambah Mahasiswa' untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="p-6 border-t border-gray-50 bg-slate-50/30">
                    {{ $students->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Add/Edit Student -->
        <div x-cloak :class="showAddModal ? 'pointer-events-auto' : 'pointer-events-none'"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">

            <div @click="showAddModal = false"
                class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                :class="showAddModal ? 'opacity-100' : 'opacity-0'"></div>

            <div @click.stop :aria-hidden="(!showAddModal).toString()" :inert="!showAddModal"
                class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                :class="showAddModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800" x-text="editMode ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru'">
                    </h3>
                    <button @click="showAddModal = false"
                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form
                    :action="editMode ? '{{ url('admin/students') }}/' + currentStudent.id : '{{ route('admin.students.store') }}'"
                    method="POST" class="p-5 space-y-3">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" :value="currentStudent.name" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
                            placeholder="Contoh: Ahmad Subardjo">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">NIM</label>
                            <input type="text" name="nim" :value="currentStudent.nim" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
                                placeholder="NIM">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Prodi</label>
                            <input type="text" name="prodi" :value="currentStudent.prodi" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm"
                                placeholder="TI">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">Pilih Mata Kuliah</label>
                        <div class="grid grid-cols-3 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100 max-h-[120px] overflow-y-auto">
                            @foreach($subjects as $subject)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" 
                                    :checked="selectedSubjects.includes({{ $subject->id }})"
                                    class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                                <span class="text-[11px] font-medium text-slate-600 group-hover:text-blue-600 transition-colors truncate">{{ $subject->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-3 flex gap-3">
                        <button type="button" @click="showAddModal = false"
                            class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-lg hover:bg-slate-200 transition-all text-sm">Batal</button>
                        <button type="submit"
                            class="flex-[2] bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm"
                            x-text="editMode ? 'Simpan Perubahan' : 'Simpan Mahasiswa'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Import Excel -->
        <div x-cloak :class="showImportModal ? 'pointer-events-auto' : 'pointer-events-none'"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">

            <div @click="showImportModal = false"
                class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                :class="showImportModal ? 'opacity-100' : 'opacity-0'"></div>

            <div @click.stop :aria-hidden="(!showImportModal).toString()" :inert="!showImportModal"
                class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                :class="showImportModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Import Data Mahasiswa</h3>
                    <button @click="showImportModal = false"
                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data"
                    class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                            <p class="text-xs text-blue-700 leading-relaxed font-medium">
                                <i class="fas fa-info-circle mr-1"></i> Pastikan file Excel memiliki heading: <strong>nama,
                                    nim, prodi, mata kuliah</strong>. Kolom <strong>mata kuliah</strong> harus cocok dengan data mata
                                kuliah yang ada di sistem.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Pilih File
                                Excel</label>
                            <input type="file" name="file" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-xs">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-emerald-600 text-white font-bold py-4 rounded-lg hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100">
                            Mulai Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                fireAppSwal({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                });
            });
        </script>
    @endif
@endsection