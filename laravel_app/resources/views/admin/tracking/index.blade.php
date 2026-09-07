@extends('layouts.app')

@section('title', 'Tracking Mahasiswa')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Tracking Mahasiswa Real-Time</h2>
    <p class="text-slate-500 text-sm">Pantau status online mahasiswa dan kelola akses akun secara instan.</p>
</div>

<!-- Filter & Search -->
<div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6">
    <form action="{{ route('admin.tracking.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[250px] space-y-1.5">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cari Mahasiswa</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama atau NIM..."
                    class="w-full pl-11 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm">
            </div>
        </div>
        
        <div class="w-full sm:w-[150px] space-y-1.5">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Status</label>
            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all text-sm text-slate-700">
                <option value="">Semua Status</option>
                <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>🟢 Online</option>
                <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>⚫ Offline</option>
            </select>
        </div>

        <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-slate-900 transition-all shadow-sm">
            Filter
        </button>
        @if(request()->filled('search') || request()->filled('status'))
            <a href="{{ route('admin.tracking.index') }}" class="bg-slate-100 text-slate-600 px-3.5 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-all">
                Reset
            </a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                <tr>
                    <th class="px-4 py-3">Nama / NIM</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Terakhir Dilihat</th>
                    <th class="px-4 py-3 text-center">Akses Akun</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($students as $student)
                    @php
                        $isOnline = $student->last_seen_at && $student->last_seen_at->diffInMinutes(now()) < 3;
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $student->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium font-mono">{{ $student->nim ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($isOnline)
                                <div class="badge-online">
                                    <span class="dot-wrapper">
                                      <span class="dot-ping"></span>
                                      <span class="dot-core"></span>
                                    </span>
                                    Online
                                </div>
                            @else
                                <div class="badge-offline">
                                    <span class="dot-offline"></span>
                                    Offline
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">
                            {{ $student->last_seen_at ? $student->last_seen_at->diffForHumans() : 'Belum pernah login' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center items-center">
                                <label for="toggle-{{ $student->id }}" class="custom-toggle-label group" style="cursor: pointer; display: flex; align-items: center;">
                                    <div style="position: relative;">
                                        <input type="checkbox" id="toggle-{{ $student->id }}" class="sr-only toggle-access" 
                                            data-id="{{ $student->id }}" 
                                            data-name="{{ $student->name }}" 
                                            {{ $student->is_active ? 'checked' : '' }}>
                                        <div class="toggle-bg"></div>
                                        <div class="toggle-dot flex items-center justify-center">
                                            <i class="fas fa-power-off dot-icon"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="fas fa-users-viewfinder text-6xl mb-4"></i>
                                <p class="text-lg font-bold">Tidak ada data mahasiswa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="p-6 border-t border-gray-50" style="background-color: rgba(248, 250, 252, 0.3);">
            {{ $students->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<style>
    /* Status Badge CSS */
    .badge-online {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 9999px;
        background-color: #ecfdf5; border: 1px solid #d1fae5;
        color: #059669; font-size: 0.75rem; font-weight: 700;
    }
    .badge-offline {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 9999px;
        background-color: #f1f5f9; border: 1px solid #e2e8f0;
        color: #64748b; font-size: 0.75rem; font-weight: 700;
    }
    .dot-wrapper { position: relative; display: flex; height: 10px; width: 10px; }
    .dot-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        position: absolute; display: inline-flex; height: 100%; width: 100%;
        border-radius: 9999px; background-color: #34d399; opacity: 0.75;
    }
    .dot-core {
        position: relative; display: inline-flex; border-radius: 9999px;
        height: 10px; width: 10px; background-color: #10b981;
    }
    .dot-offline {
        position: relative; display: inline-flex; border-radius: 9999px;
        height: 10px; width: 10px; background-color: #94a3b8;
    }
    @keyframes ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }

    /* Premium iOS-like Toggle Switch CSS */
    .toggle-bg {
        display: block; width: 50px; height: 28px; border-radius: 9999px;
        background-color: #e2e8f0; border: 1px solid rgba(226, 232, 240, 0.5);
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        transition: background-color 0.3s, border-color 0.3s;
    }
    .toggle-dot {
        position: absolute; left: 4px; top: 4px; background-color: #ffffff;
        width: 20px; height: 20px; border-radius: 9999px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s;
    }
    .dot-icon {
        font-size: 10px; color: #cbd5e1; transition: color 0.3s;
    }
    
    input:checked ~ .toggle-bg {
        background-color: #10B981; /* Emerald 500 */
        border-color: #059669;
    }
    input:checked ~ .toggle-dot {
        transform: translateX(22px);
    }
    input:checked ~ .toggle-dot .dot-icon {
        color: #10B981;
    }
</style>
<script>
    document.querySelectorAll('.toggle-access').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            // Checkbox.checked saat event click ini DIBACA SEBAGAI STATE BARU (state tujuan)
            // Jadi willBeActive adalah nilai checkbox.checked saat ini
            const willBeActive = this.checked;
            const currentlyActive = !willBeActive; // state aslinya sebelum diklik
            
            // Mencegah checkbox berubah bentuk sebelum dikonfirmasi 
            // (preventDefault akan mengembalikan visual ke state asli setelah event selesai)
            e.preventDefault();
            
            const checkbox = this;
            const userId = checkbox.getAttribute('data-id');
            const userName = checkbox.getAttribute('data-name');
            
            const actionText = willBeActive ? 'Aktifkan Akun' : 'Cabut Akses';
            const titleText = willBeActive ? 'Aktifkan Kembali?' : 'Nonaktifkan Akun?';
            const confirmColor = willBeActive ? '#10B981' : '#EF4444'; // Emerald vs Red
            const iconType = willBeActive ? 'question' : 'warning';
            const textBody = willBeActive 
                ? `Mahasiswa <b class="text-lg text-emerald-600">${userName}</b> akan dapat login dan mengakses sistem kembali.` 
                : `Mahasiswa <b class="text-lg text-red-600">${userName}</b> akan <b>ditendang keluar (Force Logout)</b> secara paksa saat ini juga!`;

            Swal.fire({
                title: titleText,
                html: textBody,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: `<i class="fas fa-power-off mr-1"></i> Ya, ${actionText}`,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'shadow-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Update UI secara optimis setelah konfirmasi
                    checkbox.checked = willBeActive;

                    fetch(`/admin/tracking/${userId}/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            checkbox.checked = currentlyActive; // Kembalikan jika server gagal
                            Swal.fire('Gagal!', data.message || 'Gagal mengubah status', 'error');
                        } else {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        checkbox.checked = currentlyActive; // Kembalikan jika error jaringan
                        Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
                    });
                }
            });
        });
    });
</script>
@endpush
