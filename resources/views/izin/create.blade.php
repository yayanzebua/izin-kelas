@extends('layouts.app')

@section('title', 'Ajukan Izin Baru')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Ajukan Izin Baru</h4>
                    <small class="text-muted">Isi formulir berikut untuk mengajukan permohonan izin</small>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('izin.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                       value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                       value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alasan Izin <span class="text-danger">*</span></label>
                                <textarea name="alasan" class="form-control @error('alasan') is-invalid @enderror"
                                          rows="4" placeholder="Jelaskan alasan izin Anda (minimal 10 karakter)..."
                                          required minlength="10" maxlength="1000">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimal 10 karakter, maksimal 1000 karakter.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                          rows="3" placeholder="Keterangan tambahan (opsional)..."
                                          maxlength="1000">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - tambahkan informasi pelengkap jika diperlukan.</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Ajukan Permohonan
                            </button>
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
