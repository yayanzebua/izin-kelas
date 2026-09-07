@extends('layouts.app')

@section('title', 'Kontak Dosen')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Kontak Dosen</h2>
        <p class="text-slate-500 text-sm">Hubungi dosen pengampu untuk urusan perkuliahan.</p>
    </div>

    @php
        $statusMap = [
            'JARINGAN NIRKABEL' => 'Tatap Muka',
            'KEAMANAN KOMPUTER' => 'Tatap Muka',
            'PEMROGRAMAN WEB II' => 'Tatap Muka',
            'KECAKAPAN ANTAR PERSONAL' => 'Tatap Muka',
            'ETIKA PROFESI' => 'Online',
            'ARSITEKTUR DAN ORGANISASI KOMPUTER' => 'Online',
            'TESTING DAN QA PERANGKAT LUNAK' => 'Online',
            'MANAJEMEN PROYEK INFORMATIKA' => 'Online',
        ];
    @endphp

    <div x-data="{ filterStatus: 'all' }" class="flex flex-col gap-4">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <button @click="filterStatus = 'all'" 
                :class="filterStatus === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'" 
                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                Semua
            </button>
            <button @click="filterStatus = 'Tatap Muka'" 
                :class="filterStatus === 'Tatap Muka' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'" 
                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                Tatap Muka
            </button>
            <button @click="filterStatus = 'Online'" 
                :class="filterStatus === 'Online' ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'" 
                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                Online
            </button>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left responsive-table">
                    <thead
                        class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                        <tr>
                            <th class="px-4 py-3">Nama Dosen</th>
                            <th class="px-4 py-3">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($lecturers as $lecturer)
                            @php
                                $subjectName = strtoupper(trim($lecturer->subject));
                                $status = $statusMap[$subjectName] ?? 'Online'; 
                            @endphp
                            <tr x-show="filterStatus === 'all' || filterStatus === '{{ $status }}'" 
                                x-transition
                                class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ substr($lecturer->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[14px] font-bold text-slate-800 truncate" title="{{ $lecturer->name }}">{{ $lecturer->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-medium text-slate-500">
                                    {{ $lecturer->subject ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest {{ $status === 'Online' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center w-32">
                                    <button
                                        onclick="confirmContact({{ $lecturer->id }}, '{{ $lecturer->phone }}', '{{ addslashes($lecturer->name) }}')"
                                        class="w-full flex items-center justify-center gap-1.5 text-white py-1.5 px-3 rounded-lg font-bold text-[11px] transition-all shadow-sm hover:shadow-md hover:scale-[1.02]"
                                        style="background-color: #25D366;">
                                        <i class="fab fa-whatsapp text-[13px]"></i>
                                        Hubungi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fas fa-address-book text-5xl mb-3"></i>
                                        <p class="text-sm font-bold">Belum ada kontak dosen yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <style>
        /* Mobile Responsive Table CSS */
        @media (max-width: 768px) {
            .responsive-table, .responsive-table tbody, .responsive-table tr, .responsive-table td {
                display: block !important;
                width: 100% !important;
            }
            .responsive-table thead {
                display: none;
            }
            .responsive-table tr {
                margin-bottom: 0.5rem;
                padding-bottom: 0.5rem;
            }
            .responsive-table td {
                padding: 0.25rem 1rem !important;
                text-align: left !important;
                border: none !important;
            }
            .responsive-table td:first-child {
                padding-top: 1rem !important;
            }
            
            /* Subject label */
            .responsive-table td:nth-child(2)::before {
                content: "MATA KULIAH";
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                color: #94a3b8;
                display: block;
                margin-bottom: 2px;
                letter-spacing: 0.05em;
            }
            
            /* Status flex container */
            .responsive-table td:nth-child(3) {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                margin-top: 0.5rem;
                padding-top: 0.5rem !important;
                border-top: 1px solid #f1f5f9 !important;
            }
            .responsive-table td:nth-child(3)::before {
                content: "STATUS";
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                color: #94a3b8;
                letter-spacing: 0.05em;
            }
            
            .responsive-table td:nth-child(4) {
                padding-bottom: 1rem !important;
            }
        }

        .wa-btn-swal {
            background-color: #25D366 !important;
            color: white !important;
            border: none !important;
        }

        .wa-btn-swal:hover {
            background-color: #128C7E !important;
        }

        .blink-warning {
            animation: blinker 1.5s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0.3;
            }
        }
    </style>
    <script>
        function confirmContact(lecturerId, phone, lecturerName) {
            if (typeof fireAppSwal !== 'undefined') {
                fireAppSwal({
                    title: 'Hubungi Dosen?',
                    html: `Anda akan diarahkan ke WhatsApp untuk menghubungi<br><b class="text-lg">${lecturerName}</b>.
                                                               <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-[11px] uppercase tracking-wider font-black blink-warning">
                                                                   <i class="fas fa-exclamation-triangle mr-1"></i> Hati-hati, aksi ini direkam dan terdeteksi oleh admin!
                                                               </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fab fa-whatsapp text-sm mr-1"></i> Ya, Hubungi WA',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'app-swal-btn wa-btn-swal shadow-lg shadow-green-200',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        processContact(lecturerId, phone, lecturerName);
                    }
                });
            } else {
                if (confirm(`Hubungi ${lecturerName} via WhatsApp?`)) {
                    processContact(lecturerId, phone, lecturerName);
                }
            }
        }

        function processContact(lecturerId, phone, lecturerName) {
            // Tampilkan loader saat memproses
            const loader = document.getElementById('global-loader');
            if (loader) loader.classList.add('active');

            // Pastikan phone formatnya 628...
            let waNumber = phone.replace(/[^0-9]/g, '');
            if (waNumber.startsWith('08')) {
                waNumber = '628' + waNumber.substring(2);
            }

            // Generate greeting berdasarkan jam
            const hour = new Date().getHours();
            let greeting = 'Malam';
            if (hour >= 3 && hour < 11) greeting = 'Pagi';
            else if (hour >= 11 && hour < 15) greeting = 'Siang';
            else if (hour >= 15 && hour < 18) greeting = 'Sore';

            // Bersihkan gelar dosen (hapus setelah koma dan hapus prefix umum)
            let cleanName = lecturerName.split(',')[0];
            cleanName = cleanName.replace(/^(Dr\.|Prof\.|Ir\.|Drs\.|Dra\.|H\.|Hj\.)\s*/gi, '').trim();

            // Deteksi Pak / Ibu berdasarkan nama
            const femaleNames = ['rinna', 'dede', 'badriah', 'siti', 'ayu', 'putri', 'nur', 'sri', 'indah', 'dewi', 'ratna', 'sari', 'dina', 'eka', 'fitri', 'dwi', 'tuti'];
            let title = 'Bapak'; // Default
            const lowerName = cleanName.toLowerCase();
            for (let fname of femaleNames) {
                if (lowerName.includes(fname)) {
                    title = 'Ibu';
                    break;
                }
            }

            // Siapkan template pesan
            const studentName = '{{ Auth::user()->name }}';
            const studentNim = '{{ Auth::user()->nim ?? "-" }}';
            const waMessage = `Assalamualaikum wr.wb, \nSelamat ${greeting} ${title} ${cleanName} \nSaya \t: ${studentName}\nNim \t: ${studentNim} \nKelas \t: {{ \App\Models\Setting::get('class_code', '07TPLE018') }}, Ruang V.B56 \n\n [Sampaikan dengan jelas dan sopan dan kirim bukti gambar/file sebagai pendukung.]`;
            const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(waMessage)}`;

            // Kirim request ke server untuk tracking dan notifikasi Telegram Admin
            fetch(`/lecturers/${lecturerId}/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (loader) loader.classList.remove('active');
                    
                    if (data.status === 'approved') {
                        window.location.href = waUrl;
                    } else if (data.status === 'pending') {
                        // Tampilkan modal menunggu yang elegan
                        Swal.fire({
                            html: `
                                <div class="flex flex-col items-center justify-center pt-6 pb-2 px-2">
                                    <div class="w-24 h-24 mb-6 rounded-full bg-blue-50 flex items-center justify-center relative">
                                        <div class="absolute inset-0 rounded-full border-4 border-blue-100 animate-ping opacity-75"></div>
                                        <i class="fab fa-telegram-plane text-4xl text-blue-500 relative z-10 ml-1 mt-1"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Menunggu Persetujuan</h3>
                                    <p class="text-slate-500 text-[13px] text-center mb-8 leading-relaxed px-4">
                                        Permintaan akses WhatsApp Anda telah diteruskan secara realtime ke Telegram Admin.<br>
                                        <b class="text-slate-700">Mohon tunggu sebentar...</b>
                                    </p>
                                    <div class="flex items-center gap-2.5 bg-slate-50 px-5 py-3 rounded-full border border-slate-100 shadow-inner">
                                        <i class="fas fa-circle-notch fa-spin text-blue-500 text-sm"></i>
                                        <span class="text-[11px] font-bold text-slate-500 tracking-[0.1em]">MENGECEK STATUS...</span>
                                    </div>
                                </div>
                            `,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            customClass: {
                                popup: 'rounded-[2rem] shadow-2xl border border-slate-100/50',
                            }
                        });
                        
                        // Mulai polling
                        let pollInterval = setInterval(() => {
                            fetch(`/lecturers/${lecturerId}/contact/status?t=${new Date().getTime()}`)
                                .then(res => res.json())
                                .then(statusData => {
                                    if (statusData.status === 'approved') {
                                        clearInterval(pollInterval);
                                        Swal.fire({
                                            html: `
                                                <div class="flex flex-col items-center justify-center pt-6 pb-2 px-2">
                                                    <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center relative overflow-hidden" style="background-color: #d1fae5;">
                                                        <div class="absolute inset-0 animate-pulse opacity-50" style="background-color: #a7f3d0;"></div>
                                                        <i class="fas fa-check text-3xl relative z-10 animate-bounce" style="color: #10b981;"></i>
                                                    </div>
                                                    <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Akses Disetujui!</h3>
                                                    <p class="text-slate-500 text-[13px] text-center mb-2 px-4 leading-relaxed">
                                                        Admin telah memberikan izin. Anda sekarang dapat melanjutkan untuk mengirim pesan.
                                                    </p>
                                                </div>
                                            `,
                                            showConfirmButton: true,
                                            confirmButtonText: '<i class="fab fa-whatsapp text-lg mr-2"></i> Lanjutkan ke WhatsApp',
                                            buttonsStyling: false,
                                            customClass: {
                                                popup: 'rounded-[2rem] shadow-2xl border border-emerald-50',
                                                confirmButton: 'wa-btn-swal w-full mt-4 font-bold py-3.5 px-6 rounded-2xl shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center text-sm',
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = waUrl;
                                            }
                                        });
                                    } else if (statusData.status === 'rejected') {
                                        clearInterval(pollInterval);
                                        Swal.fire({
                                            html: `
                                                <div class="flex flex-col items-center justify-center pt-6 pb-2 px-2">
                                                    <div class="w-20 h-20 mb-6 rounded-full flex items-center justify-center" style="background-color: #fee2e2;">
                                                        <i class="fas fa-times text-3xl" style="color: #ef4444;"></i>
                                                    </div>
                                                    <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Akses Ditolak</h3>
                                                    <p class="text-slate-500 text-[13px] text-center px-4 leading-relaxed">
                                                        Maaf, permintaan Anda tidak disetujui oleh Admin untuk saat ini. Silakan coba lagi nanti jika diperlukan.
                                                    </p>
                                                </div>
                                            `,
                                            showConfirmButton: true,
                                            confirmButtonText: 'Mengerti, Tutup',
                                            buttonsStyling: false,
                                            customClass: {
                                                popup: 'rounded-[2rem] shadow-2xl border border-red-50',
                                                confirmButton: 'w-full mt-6 font-bold py-3 px-6 rounded-2xl transition-all text-sm',
                                            }
                                        });
                                        
                                        // Force style directly for the close button to ensure it renders without Tailwind compiling
                                        const swalConfirmBtn = document.querySelector('.swal2-confirm');
                                        if (swalConfirmBtn) {
                                            swalConfirmBtn.style.backgroundColor = '#f1f5f9';
                                            swalConfirmBtn.style.color = '#334155';
                                        }
                                    }
                                })
                                .catch(err => {
                                    console.error('Polling error:', err);
                                });
                        }, 3000); // Polling tiap 3 detik
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (loader) loader.classList.remove('active');
                    Swal.fire('Error', 'Terjadi kesalahan saat memproses permintaan.', 'error');
                });
        }
    </script>
@endpush