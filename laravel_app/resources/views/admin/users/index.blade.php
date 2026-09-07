@extends('layouts.app')

@section('title', 'Kelola Akun User')

@section('content')
    <div
        x-data="{ 
            showEditModal: {{ $errors->any() ? 'true' : 'false' }}, 
            currentUser: @js(old('id') ? \App\Models\User::find(old('id')) : (object)[])
        }">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Kelola Akun User</h2>
            <p class="text-slate-500 text-sm">Manajemen username, email, dan password untuk semua pengguna sistem.</p>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[250px] space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cari User</label>
                    <div class="relative group">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, email, atau NIM..."
                            class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm">
                    </div>
                </div>
                <div class="w-48 space-y-1.5">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Role</label>
                    <select name="role" class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm appearance-none">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-slate-900 transition-all shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-slate-100 text-slate-600 px-3.5 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead
                        class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                        <tr>
                            <th class="px-4 py-3">Nama / Username</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">NIM</th>
                            <th class="px-4 py-3 text-center">Role</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">ID: #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $user->email ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->nim)
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono font-bold">{{ $user->nim }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="currentUser = @js($user); showEditModal = true"
                                            class="flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg transition-all text-xs font-bold">
                                            <i class="fas fa-edit"></i> Edit Akun
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-user-{{ $user->id }}', 'Akun {{ addslashes($user->name) }} akan dihapus secara permanen!')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fas fa-users-cog text-6xl mb-4"></i>
                                        <p class="text-lg font-bold">Tidak ada data user</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-6 border-t border-gray-50 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Edit User Account -->
        <div x-cloak :class="showEditModal ? 'pointer-events-auto' : 'pointer-events-none'"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4">

            <div @click="showEditModal = false"
                class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                :class="showEditModal ? 'opacity-100' : 'opacity-0'"></div>

            <div @click.stop :aria-hidden="(!showEditModal).toString()" :inert="!showEditModal"
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                :class="showEditModal ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Edit Akun User</h3>
                        <p class="text-xs text-slate-500">Sesuaikan informasi login dan kredensial.</p>
                    </div>
                    <button @click="showEditModal = false"
                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form
                    :action="'{{ url('admin/users') }}/' + currentUser.id"
                    method="POST" class="p-5 space-y-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" :value="currentUser.id">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nama / Username</label>
                        <input type="text" name="name" :value="currentUser.name" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                            placeholder="Username">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Email</label>
                            <input type="email" name="email" :value="currentUser.email"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                                placeholder="Email (opsional)">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">NIM</label>
                            <input type="text" name="nim" :value="currentUser.nim"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                                placeholder="NIM (opsional)">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-dashed border-slate-200 mt-4">
                        <div class="mb-3 p-2.5 bg-amber-50 border border-amber-100 rounded-lg flex gap-2">
                            <i class="fas fa-exclamation-triangle text-amber-500 text-xs mt-0.5"></i>
                            <p class="text-[10px] text-amber-700 leading-tight font-medium">
                                Kosongkan password jika tidak ingin mengubahnya. Minimal 6 karakter.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Password Baru</label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                                    placeholder="••••••••">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showEditModal = false"
                            class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-lg hover:bg-slate-200 transition-all text-sm">Batal</button>
                        <button type="submit"
                            class="flex-[2] bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm">
                            Simpan Perubahan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
