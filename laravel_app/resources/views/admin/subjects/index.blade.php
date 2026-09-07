@extends('layouts.app')

@section('title', 'Manajemen Mata Kuliah')

@section('content')
    <div x-data="{ showAddModal: false, editMode: false, currentSubject: {} }">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Mata Kuliah</h2>
            </div>
            <button @click="editMode = false; currentSubject = {}; showAddModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md shadow-blue-200 transition-all flex items-center gap-2 group">
                <i class="fas fa-plus transition-transform group-hover:rotate-90"></i> Tambah Mata Kuliah
            </button>
        </div>

        @if(session('error'))
            <div
                class="mb-6 p-4 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded shadow-sm flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($subjects as $subject)
                <div
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative group">
                    <div class="flex items-start justify-between">
                        <div
                            class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg mb-4">
                            <i class="fas fa-building-columns"></i>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" @click="editMode = true; currentSubject = @js($subject); showAddModal = true"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <form id="delete-subject-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-subject-{{ $subject->id }}', 'Mata kuliah {{ $subject->name }} akan dihapus secara permanen!')"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-600 hover:text-white transition-all">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base leading-tight mb-2">{{ $subject->name }}</h3>
                    <div class="flex items-center gap-2 text-xs text-gray-400 font-bold uppercase tracking-widest">
                        <i class="fas fa-users"></i>
                        <span>{{ $subject->students()->count() }} Mahasiswa</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Add/Edit Subject -->
        <div x-cloak :class="showAddModal ? 'pointer-events-auto' : 'pointer-events-none'"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">

            <div @click="showAddModal = false"
                class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                :class="showAddModal ? 'opacity-100' : 'opacity-0'"></div>

            <div @click.stop :aria-hidden="(!showAddModal).toString()" :inert="!showAddModal"
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                :class="showAddModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-3.5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-800"
                        x-text="editMode ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah Baru'"></h3>
                    <button @click="showAddModal = false"
                        class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <form
                    :action="editMode ? '{{ url('admin/subjects') }}/' + currentSubject.id : '{{ route('admin.subjects.store') }}'"
                    method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nama Mata
                            Kuliah</label>
                        <input type="text" name="name" :value="currentSubject.name" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                            placeholder="Contoh: Rekayasa Perangkat Lunak">
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="showAddModal = false"
                            class="flex-1 bg-slate-100 text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition-all text-sm">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm"
                            x-text="editMode ? 'Simpan Perubahan' : 'Simpan Data'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection