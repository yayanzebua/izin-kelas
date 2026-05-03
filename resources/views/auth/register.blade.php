<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Aplikasi Izin Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 1rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="text-white fw-bold"><i class="bi bi-journal-check me-2"></i>Izin Kelas</h2>
                    <p class="text-white-50">Daftar sebagai siswa</p>
                </div>
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title text-center mb-4">Buat Akun Baru</h5>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Nama lengkap Anda" required autofocus>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="email@contoh.com" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kelas</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                    <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror"
                                           value="{{ old('kelas') }}" placeholder="Contoh: 10A, 11B, 12C" required maxlength="10">
                                </div>
                                <div class="form-text">Masukkan kelas Anda (contoh: 10A, 11B, 12C)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-transparent py-3">
                        <span class="text-muted">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
