@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Pengaturan Sistem</h2>
    <p class="text-slate-500 text-sm">Kelola konfigurasi fitur dan pengalaman pengguna.</p>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-50">
        <h3 class="font-bold text-lg text-slate-800">Interaksi Pengguna (User Experience)</h3>
        <p class="text-sm text-slate-500 mb-6">Aktifkan atau nonaktifkan fitur interaktif khusus untuk pengguna (mahasiswa).</p>

        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                    <h4 class="font-bold text-slate-800">Onboarding "Kontak Dosen"</h4>
                </div>
                <p class="text-sm text-slate-500 max-w-xl">
                    Tampilkan panduan interaktif layar penuh (wajib diselesaikan) mengenai cara menggunakan fitur pesan instan Dosen ketika mahasiswa baru pertama kali masuk.
                </p>
                <p class="text-[11px] text-blue-600 font-bold mt-2 bg-blue-100 px-2 py-1 rounded inline-block">
                    <i class="fas fa-info-circle mr-1"></i> Jika diaktifkan ulang, seluruh mahasiswa yang belum melihatnya (atau yang di-reset) akan mendapatkan pop-up ini lagi.
                </p>
            </div>
            
            <label for="toggle-onboarding" class="custom-toggle-label group" style="cursor: pointer; display: flex; align-items: center;">
                <div style="position: relative;">
                    <input type="checkbox" id="toggle-onboarding" class="sr-only toggle-setting" 
                        {{ $settings['onboarding_active'] ? 'checked' : '' }}>
                    <div class="toggle-bg"></div>
                    <div class="toggle-dot flex items-center justify-center">
                        <i class="fas fa-power-off dot-icon"></i>
                    </div>
                </div>
            </label>
        </div>
        <hr class="my-6 border-gray-100">

        <h3 class="font-bold text-lg text-slate-800">Identitas Sistem</h3>
        <p class="text-sm text-slate-500 mb-6">Konfigurasi data identitas utama aplikasi SIPEKA.</p>

        <form id="form-class-code" class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 gap-4 mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-university text-blue-600"></i>
                    <h4 class="font-bold text-slate-800">Kode Kelas / ID Sistem</h4>
                </div>
                <p class="text-sm text-slate-500 max-w-xl mb-3">
                    Kode ini akan ditampilkan di logo SIPEKA, Dashboard, dan template otomatis pesan WhatsApp Mahasiswa.
                </p>
                <div class="relative">
                    <input type="text" id="class-code-input" value="{{ $settings['class_code'] ?? '07TPLE018' }}" class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm uppercase font-bold text-slate-700" placeholder="07TPLE018" required>
                </div>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2 shrink-0 w-full md:w-auto">
                <i class="fas fa-save"></i> Simpan Kode
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<style>
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
document.getElementById('toggle-onboarding').addEventListener('click', function(e) {
    e.preventDefault();
    const checkbox = this;
    const willBeActive = checkbox.checked;
    const currentlyActive = !willBeActive;

    const actionText = willBeActive ? 'Aktifkan' : 'Matikan';
    const titleText = willBeActive ? 'Aktifkan Onboarding?' : 'Matikan Onboarding?';
    
    Swal.fire({
        title: titleText,
        text: willBeActive ? 'Mahasiswa akan melihat panduan wajib saat login. Status mahasiswa akan di-reset.' : 'Panduan akan disembunyikan dari semua mahasiswa.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: willBeActive ? '#10B981' : '#EF4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: `Ya, ${actionText}`,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            checkbox.checked = willBeActive;

            fetch('{{ route('admin.settings.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    onboarding_active: willBeActive ? 'true' : 'false',
                    class_code: document.getElementById('class-code-input').value.toUpperCase()
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    checkbox.checked = currentlyActive;
                    Swal.fire('Error', 'Gagal memperbarui pengaturan.', 'error');
                }
            })
            .catch(err => {
                checkbox.checked = currentlyActive;
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            });
        }
    });
});

document.getElementById('form-class-code').addEventListener('submit', function(e) {
    e.preventDefault();
    const classCode = document.getElementById('class-code-input').value.toUpperCase();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    fetch('{{ route('admin.settings.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            onboarding_active: document.getElementById('toggle-onboarding').checked ? 'true' : 'false',
            class_code: classCode
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: 'Kode kelas berhasil diperbarui ke seluruh sistem.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Terjadi kesalahan jaringan saat menyimpan.', 'error');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
@endpush
