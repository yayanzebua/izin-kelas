@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard')

@section('content')
    @php
        $showOnboarding = \App\Models\Setting::get('onboarding_active') === 'true' && !auth()->user()->has_seen_onboarding;
    @endphp

    @if($showOnboarding)
    <div x-data="{ 
            step: 1, 
            maxSteps: 3, 
            submitting: false,
            completeOnboarding() {
                if (this.submitting) return;
                this.submitting = true;
                fetch('{{ route('onboarding.complete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        this.submitting = false;
                        alert('Terjadi kesalahan, silakan coba lagi.');
                    }
                }).catch(() => {
                    this.submitting = false;
                    alert('Kesalahan jaringan.');
                });
            }
        }" 
        class="fixed inset-0 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md pointer-events-auto"
        style="z-index: 99999;">
        
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl relative flex flex-col" style="height: 550px; max-height: calc(100vh - 2rem);">
            <!-- Progress Bar -->
            <div class="flex gap-2 p-6 pb-2">
                <div class="h-1.5 rounded-full flex-1 transition-colors duration-300" :class="step >= 1 ? 'bg-blue-600' : 'bg-slate-200'"></div>
                <div class="h-1.5 rounded-full flex-1 transition-colors duration-300" :class="step >= 2 ? 'bg-blue-600' : 'bg-slate-200'"></div>
                <div class="h-1.5 rounded-full flex-1 transition-colors duration-300" :class="step >= 3 ? 'bg-blue-600' : 'bg-slate-200'"></div>
            </div>

            <!-- Slider Content -->
            <div class="flex-1 relative overflow-hidden w-full h-full bg-slate-50/50">
                <!-- Slide 1: Welcome -->
                <div x-show="step === 1" 
                     x-transition:enter="transition ease-out duration-500" 
                     x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-300 absolute"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-12 scale-95"
                     class="absolute inset-0 p-8 flex flex-col items-center justify-center text-center">
                    
                    <!-- Blinking NEW Badge -->
                    <div class="absolute" style="top: 24px; right: 24px; z-index: 50; animation: badge-kedap-kedip 1s ease-in-out infinite alternate;">
                        <!-- Main Badge Container (No Outer Shadow) -->
                        <div class="relative px-3 py-1 rounded-full flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                            <!-- Crisp Professional Typography -->
                            <span class="text-white font-extrabold text-[10px] tracking-widest uppercase relative z-10" style="font-family: system-ui, -apple-system, sans-serif; letter-spacing: 0.15em;">NEW</span>
                        </div>
                        
                        <style>
                            @keyframes badge-kedap-kedip {
                                0% { opacity: 0.3; transform: scale(0.95); }
                                100% { opacity: 1; transform: scale(1.05); }
                            }
                        </style>
                    </div>

                    <!-- Ultimate Premium WhatsApp Icon -->
                    <div class="relative flex justify-center items-center w-full" style="height: 6rem; margin-bottom: 3.5rem; margin-top: 1rem;">
                        <!-- Soft Ambient Glow -->
                        <div class="absolute w-24 h-24 rounded-full blur-xl opacity-40 animate-pulse" style="background-color: #25D366;"></div>
                        
                        <!-- Radar Ripple -->
                        <div class="absolute w-20 h-20 rounded-full animate-ping opacity-60" style="background-color: #25D366; animation-duration: 2.5s;"></div>
                        
                        <!-- 3D Circular Container -->
                        <div class="relative w-20 h-20 rounded-full flex items-center justify-center border-4 z-10 transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, #4ade80 0%, #25D366 50%, #128C7E 100%); border-color: #ffffff; box-shadow: 0 12px 25px -4px rgba(37, 211, 102, 0.5), inset 0 -4px 6px rgba(0,0,0,0.1);">
                            <!-- Top Glass Highlight -->
                            <div class="absolute top-0 left-0 right-0 h-1/2 rounded-t-full pointer-events-none" style="background: linear-gradient(180deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 100%);"></div>
                            
                            <!-- Perfectly Proportioned Icon -->
                            <i class="fab fa-whatsapp" style="color: #ffffff; font-size: 2.75rem; filter: drop-shadow(0 3px 3px rgba(0,0,0,0.2)); margin-bottom: 2px; margin-right: 2px;"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-3 leading-tight">
                        Hai {{ explode(' ', Auth::user()->name)[0] }}!<br>
                        <span class="text-blue-600">Fitur Kontak Dosen Telah Hadir</span>
                    </h3>
                    <p class="text-slate-500 text-sm leading-relaxed px-4">Sekarang Anda dapat menghubungi dosen secara langsung melalui WhatsApp untuk keperluan mendesak atau izin kelas langsung dari dasbor Anda.</p>
                </div>

                <!-- Slide 2: How to use -->
                <div x-show="step === 2" 
                     x-transition:enter="transition ease-out duration-500" 
                     x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-300 absolute"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-12 scale-95"
                     class="absolute inset-0 p-6 flex flex-col items-center justify-center text-center" style="display: none;">
                    <h3 class="text-xl font-black text-slate-800 mb-4">Sangat Mudah Digunakan</h3>
                    
                    <!-- UI Mockup -->
                    <div class="w-full bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-left mb-6 relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 font-bold flex items-center justify-center">D</div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">Dosen Pengampu</div>
                                    <div class="text-[10px] text-slate-400">PEMROGRAMAN WEB</div>
                                </div>
                            </div>
                            <button class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-1">
                                <i class="fab fa-whatsapp"></i> Hubungi WA
                            </button>
                        </div>
                        
                        <!-- Floating Cursor Animation -->
                        <div class="absolute" style="top: 24px; right: 40px; animation: cursor-click 2s ease-in-out infinite; z-index: 20;">
                            <i class="fas fa-mouse-pointer text-2xl text-slate-800 drop-shadow-lg transform -rotate-12"></i>
                            <div class="absolute" style="top: -8px; left: -8px; width: 32px; height: 32px; background-color: rgba(16, 185, 129, 0.3); border-radius: 9999px; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;"></div>
                        </div>
                        <style>
                            @keyframes cursor-click {
                                0%, 100% { transform: translate(15px, 15px); }
                                50% { transform: translate(0px, 0px); }
                            }
                        </style>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed px-2">Buka menu navigasi <b>Kontak Dosen</b>, lalu klik tombol hijau pada nama dosen yang dituju.</p>
                </div>

                <!-- Slide 3: Warning -->
                <div x-show="step === 3" 
                     x-transition:enter="transition ease-out duration-500" 
                     x-transition:enter-start="opacity-0 translate-x-12 scale-95" 
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-300 absolute"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-x-12 scale-95"
                     class="absolute inset-0 p-6 flex flex-col items-center justify-center" style="display: none;">
                    
                    <!-- Alert Mockup -->
                    <div class="w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-5 text-center mb-4 relative z-10 transform scale-95">
                        <div class="w-12 h-12 rounded-full border-4 border-blue-100 text-blue-500 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-question text-2xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 mb-1">Hubungi Dosen?</h4>
                        <p class="text-[11px] text-slate-500 mb-3">Anda akan diarahkan ke WhatsApp untuk menghubungi<br><b class="text-slate-700 text-xs mt-1 block">DOSEN PENGAMPU</b></p>
                        
                        <div class="bg-rose-50 border border-rose-200 text-rose-600 p-2 rounded-lg text-[10px] font-bold flex items-center justify-center gap-1.5 w-full mb-4">
                            <i class="fas fa-exclamation-triangle"></i> HATI-HATI, AKSI INI DIREKAM ADMIN!
                        </div>
                        
                        <div class="flex gap-2">
                            <div class="flex-1 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Batal</div>
                            <div class="flex-1 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold"><i class="fab fa-whatsapp"></i> Hubungi WA</div>
                        </div>
                    </div>

                    <p class="text-slate-600 text-[13px] leading-relaxed text-center px-2 font-medium">Gunakan bahasa yang sopan dan profesional saat menghubungi Dosen. Hak akses dapat dicabut jika terjadi penyalahgunaan.</p>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="p-6 pt-2 flex justify-end">
                <button x-show="step < maxSteps" @click="step++" type="button" class="bg-blue-600 text-white w-full py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    Lanjut <i class="fas fa-arrow-right ml-1"></i>
                </button>
                <button x-show="step === maxSteps" @click="completeOnboarding()" type="button" class="bg-slate-800 text-white w-full py-3 rounded-xl text-sm md:text-base font-bold hover:bg-slate-900 transition shadow-lg shadow-slate-200 flex justify-center items-center gap-2">
                    <span x-show="!submitting" class="truncate">Saya Mengerti & Lanjut ke Dasbor</span>
                    <i x-show="!submitting" class="fas fa-check"></i>
                    <i x-show="submitting" class="fas fa-spinner fa-spin"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }" class="relative {{ $showOnboarding ? 'opacity-0 h-0 overflow-hidden' : '' }}" @open-permission-modal.window="showModal = true" x-init="
        @if(session('success'))
            fireAppSwal({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
            });
        @endif

        try {
            const params = new URLSearchParams(window.location.search);
            if (params.get('open') === 'izin') {
                showModal = true;
                params.delete('open');
                const qs = params.toString();
                const newUrl = window.location.pathname + (qs ? ('?' + qs) : '') + window.location.hash;
                window.history.replaceState({}, '', newUrl);
            }
        } catch (e) {}
    ">
        <div class="mb-6 md:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight text-center md:text-left">
                    Halo, {{ \Illuminate\Support\Str::before(Auth::user()->name, ' ') }}! 👋
                </h2>
                <p class="text-sm text-slate-500 font-medium text-center md:text-left mt-1">Pantau status pengajuan izin Anda di sini</p>
            </div>
            <button @click="showModal = true"
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-2 group">
                <i class="fas fa-plus transition-transform group-hover:rotate-90"></i> Ajukan Izin Baru
            </button>
        </div>

        <!-- Stats Summary for Student -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5 mb-6 md:mb-8">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md hover:border-blue-100">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <p class="text-2xl font-black text-slate-800">{{ $totalPermissions }}</p>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Pengajuan</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md hover:border-indigo-100">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <p class="text-2xl font-black text-slate-800">{{ $thisMonth }}</p>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Bulan Ini</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md hover:border-amber-100">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <p class="text-2xl font-black text-slate-800">{{ $thisWeek }}</p>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Minggu Ini</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-2 transition-all hover:shadow-md hover:border-emerald-100">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-history"></i>
                    </div>
                    <p class="text-sm font-black text-slate-700 mt-2.5 text-right leading-tight">
                        {{ $lastPermissionDate ? \Carbon\Carbon::parse($lastPermissionDate)->format('d/m/Y') : '-' }}
                    </p>
                </div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">Pengajuan Terakhir</p>
            </div>
        </div>

        <!-- Riwayat Pengajuan (Card-based Layout) -->
        <div id="riwayat-pengajuan" class="mb-4 flex items-center justify-between px-1 scroll-mt-24">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pengajuan</h3>
        </div>
        
        <div class="space-y-3">
            @forelse($permissions as $item)
            <div class="bg-white p-4 md:p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group flex flex-col md:flex-row gap-4 md:items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 
                        {{ $item->type === 'sakit' ? 'bg-red-50 text-red-500' : '' }}
                        {{ $item->type === 'izin' ? 'bg-amber-50 text-amber-600' : '' }}
                        {{ $item->type === 'alpha' ? 'bg-purple-50 text-purple-500' : '' }}">
                        <i class="fas {{ $item->type === 'sakit' ? 'fa-briefcase-medical' : ($item->type === 'izin' ? 'fa-envelope-open-text' : 'fa-times-circle') }} text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</p>
                            <span class="capitalize px-2 py-0.5 rounded text-[10px] font-black tracking-wider
                                {{ $item->type === 'sakit' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $item->type === 'izin' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $item->type === 'alpha' ? 'bg-purple-100 text-purple-600' : '' }}">
                                {{ $item->type }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2 md:line-clamp-1 mb-2">{{ $item->description }}</p>
                        <p class="text-[11px] font-bold text-gray-400 flex items-center gap-1.5">
                            <i class="far fa-clock"></i> {{ $item->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-50 md:pt-0 md:border-none flex justify-end shrink-0">
                    @if($item->file)
                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 md:py-2 bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 rounded-xl font-bold text-xs transition-colors w-full md:w-auto justify-center">
                        <i class="fas fa-paperclip"></i> Lihat Bukti
                    </a>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2.5 md:py-2 bg-slate-50 text-slate-300 rounded-xl font-bold text-xs w-full md:w-auto justify-center">
                        <i class="fas fa-paperclip"></i> Tidak ada file
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white p-8 md:p-12 rounded-2xl border border-slate-100 shadow-sm text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-slate-300 text-3xl"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Pengajuan</h4>
                <p class="text-slate-500 text-sm">Anda belum pernah mengajukan izin sejauh ini.</p>
            </div>
            @endforelse
        </div>
        
        @if($permissions->hasPages())
        <div class="mt-6">
            {{ $permissions->links() }}
        </div>
        @endif

        <!-- Modal Pop-up Form (Bottom Sheet on Mobile, Centered on Desktop) -->
        <div x-cloak :class="showModal ? 'pointer-events-auto' : 'pointer-events-none'"
            class="fixed inset-0 z-[100] flex items-end md:items-center justify-center sm:p-4">

            <!-- Background overlay for closing (kept mounted; blur only when open) -->
            <div @click="showModal = false"
                class="absolute inset-0 bg-slate-900/30 transition-opacity duration-150 ease-out"
                :class="showModal ? 'opacity-100' : 'opacity-0'"></div>

            <div @click.stop :aria-hidden="(!showModal).toString()" :inert="!showModal"
                class="bg-white w-full max-w-xl rounded-t-3xl md:rounded-2xl shadow-2xl relative max-h-[90vh] md:max-h-[95vh] flex flex-col transform-gpu will-change-transform transition-transform transition-opacity duration-150 ease-out"
                :class="showModal
                    ? 'translate-y-0 opacity-100 md:scale-100'
                    : 'translate-y-full opacity-0 md:translate-y-12 md:scale-95'">

                <!-- Handle for mobile swipe indicator -->
                <div class="md:hidden flex justify-center pt-3 pb-1 w-full absolute top-0 left-0 z-20">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
                </div>

                <!-- Modal Header -->
                <div
                    class="bg-white px-5 py-3 md:py-4 border-b border-slate-100 flex justify-between items-center rounded-t-3xl md:rounded-t-2xl mt-4 md:mt-0 z-10 shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Form Laporan Izin</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Lengkapi data laporan
                            izin Anda</p>
                    </div>
                    <button @click="showModal = false"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white transition-all">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="overflow-y-auto custom-scrollbar flex-1 min-h-0">
                    <form x-ref="permissionForm" action="{{ route('permissions.store') }}" method="POST"
                        enctype="multipart/form-data" class="p-5 space-y-3" @submit.prevent="confirmSubmission()">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Pilih
                                    Mata Kuliah</label>
                                <select name="class_room_id" required
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none text-sm font-medium">
                                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Tanggal
                                    Izin</label>
                                <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}"
                                    class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Jenis
                                Izin</label>
                            <div class="flex gap-2">
                                @foreach(['sakit', 'izin'] as $type)
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="type" value="{{ $type }}" class="peer hidden" required {{ old('type') == $type ? 'checked' : '' }}>
                                        <div
                                            class="py-2 border border-slate-300 rounded-lg text-center transition-all peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white hover:border-slate-400 bg-slate-50 shadow-sm peer-checked:shadow-blue-200 peer-checked:shadow-md text-slate-800">
                                            <span class="text-xs font-black capitalize transition-colors tracking-wide">{{ $type }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Alasan /
                                Keterangan</label>
                            <textarea name="description" rows="2" required
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                                placeholder="Jelaskan alasan Anda...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-[10px] text-red-500 font-bold uppercase mt-1">
                            {{ $message }}</p> @enderror
                        </div>

                        <div x-data="{
                            fileName: '',
                            previewUrl: '',
                            objectUrl: null,
                            setFile(file) {
                                if (this.objectUrl) {
                                    URL.revokeObjectURL(this.objectUrl);
                                    this.objectUrl = null;
                                }
                                if (!file) {
                                    this.fileName = '';
                                    this.previewUrl = '';
                                    return;
                                }
                                this.fileName = file.name || '';
                                if (file.type && file.type.startsWith('image/')) {
                                    this.objectUrl = URL.createObjectURL(file);
                                    this.previewUrl = this.objectUrl;
                                } else {
                                    this.previewUrl = '';
                                }
                            },
                            clearFile() {
                                this.setFile(null);
                                if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                            }
                        }" class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Bukti
                                Pendukung</label>
                            <div class="relative group">
                                <input x-ref="fileInput" type="file" name="file"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    @change="setFile(($event.target.files && $event.target.files[0]) ? $event.target.files[0] : null)">
                                <div
                                    class="border border-dashed border-slate-300 rounded-lg p-2.5 text-center group-hover:border-blue-400 transition-colors bg-slate-50/50">
                                    <div x-show="!fileName" class="flex items-center justify-center gap-3">
                                        <i class="fas fa-cloud-upload-alt text-lg text-slate-300"></i>
                                        <div class="text-left">
                                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Upload
                                                Bukti (Maks. 5MB)</p>
                                            <p class="text-[8px] text-slate-400 font-medium">Format: JPG, PNG, atau PDF</p>
                                        </div>
                                    </div>
                                    <div x-show="fileName" class="flex items-center justify-center gap-3">
                                        <template x-if="previewUrl">
                                            <img :src="previewUrl"
                                                class="w-6 h-6 object-cover rounded border border-white shadow-sm">
                                        </template>
                                        <span class="text-[10px] font-bold text-blue-600 truncate max-w-[150px]"
                                            x-text="fileName"></span>
                                        <button type="button" @click="clearFile()"
                                            class="text-rose-500 hover:text-rose-700"><i
                                                class="fas fa-trash-alt text-[10px]"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-1">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 text-sm">
                                Kirim Laporan Izin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                function confirmSubmission() {
                    fireAppSwal({
                        title: 'Kirim Laporan?',
                        text: 'Pastikan data yang Anda isi sudah benar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kirim!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show global loader before submitting
                            const loader = document.getElementById('global-loader');
                            if (loader) loader.classList.add('active');
                            // Get the form using Alpine reference and submit it natively
                            const form = document.querySelector('[x-ref="permissionForm"]');
                            form.submit();
                        }
                    });
                }
            </script>
        @endpush
@endsection