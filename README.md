# Aplikasi Izin Kelas

Aplikasi web permohonan izin siswa berbasis Laravel. Siswa dapat mengajukan izin tidak masuk kelas secara online, dan guru dapat menyetujui atau menolaknya.

## Fitur

- **Siswa**: Mengajukan permohonan izin, melihat status permohonan, dan riwayat izin
- **Guru**: Melihat semua permohonan izin, menyetujui atau menolak dengan catatan
- **Admin**: Mengelola data pengguna dan melihat seluruh data izin

## Teknologi

- PHP 8.3 / Laravel 12
- Bootstrap 5 (via CDN)
- SQLite (database default)

## Instalasi

```bash
# Clone repository
git clone https://github.com/yayanzebua/izin-kelas.git
cd izin-kelas

# Install dependencies
composer install

# Salin file environment
cp .env.example .env
php artisan key:generate

# Buat database dan jalankan migrasi
touch database/database.sqlite
php artisan migrate --seed

# Jalankan server lokal
php artisan serve
```

Buka browser dan akses `http://localhost:8000`.

## Akun Default

| Email | Password | Role |
|-------|----------|------|
| admin@izinkelas.test | password | Admin |
| guru@izinkelas.test | password | Guru |
| siswa@izinkelas.test | password | Siswa (Kelas 10A) |

## Penggunaan

1. **Login** menggunakan salah satu akun di atas
2. **Siswa** dapat mengajukan izin baru melalui menu "Ajukan Izin"
3. **Guru** dapat mereview izin melalui menu "Semua Izin" dan mengklik tombol Review
4. **Admin** dapat mengelola pengguna melalui menu "Kelola Pengguna"
