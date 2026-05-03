@extends('layouts.app')

@section('title', 'Detail Izin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold"><i class="bi bi-file-text me-2 text-primary"></i>Detail Permohonan Izin</h4>
                    <small class="text-muted">ID: #{{ $izin->id }}</small>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Informasi Permohonan</h6>
                    <span class="badge bg-{{ $izin->status_badge }} fs-6">{{ $izin->status_label }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Nama Siswa</label>
                            <div class="fw-semibold">{{ $izin->user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Kelas</label>
                            <div class="fw-semibold">{{ $izin->user->kelas ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Mulai</label>
                            <div class="fw-semibold">{{ $izin->tanggal_mulai->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tanggal Selesai</label>
                            <div class="fw-semibold">{{ $izin->tanggal_selesai->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Durasi</label>
                            <div class="fw-semibold">
                                {{ $izin->tanggal_mulai->diffInDays($izin->tanggal_selesai) + 1 }} hari
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Alasan Izin</label>
                            <div class="p-3 bg-light rounded">{{ $izin->alasan }}</div>
                        </div>
                        @if($izin->keterangan)
                            <div class="col-12">
                                <label class="text-muted small">Keterangan Tambahan</label>
                                <div class="p-3 bg-light rounded">{{ $izin->keterangan }}</div>
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="text-muted small">Tanggal Pengajuan</label>
                            <div class="fw-semibold">{{ $izin->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($izin->status !== 'menunggu')
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-person-check me-2"></i>Informasi Review
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($izin->approvedBy)
                                <div class="col-md-6">
                                    <label class="text-muted small">Diproses oleh</label>
                                    <div class="fw-semibold">{{ $izin->approvedBy->name }}</div>
                                </div>
                            @endif
                            @if($izin->catatan_guru)
                                <div class="col-12">
                                    <label class="text-muted small">Catatan Guru</label>
                                    <div class="p-3 bg-light rounded">{{ $izin->catatan_guru }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
                @if($izin->status === 'menunggu')
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-check2-square me-2"></i>Tindakan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="text-success"><i class="bi bi-check-circle me-2"></i>Setujui Izin</h6>
                                            <form method="POST" action="{{ route('izin.approve', $izin) }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label small">Catatan (opsional)</label>
                                                    <textarea name="catatan_guru" class="form-control form-control-sm" rows="3"
                                                              placeholder="Catatan untuk siswa..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-sm w-100"
                                                        onclick="return confirm('Setujui permohonan izin ini?')">
                                                    <i class="bi bi-check-lg me-2"></i>Setujui
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-danger">
                                        <div class="card-body">
                                            <h6 class="text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Izin</h6>
                                            <form method="POST" action="{{ route('izin.reject', $izin) }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label small">Alasan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea name="catatan_guru" class="form-control form-control-sm" rows="3"
                                                              placeholder="Jelaskan alasan penolakan..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                                        onclick="return confirm('Tolak permohonan izin ini?')">
                                                    <i class="bi bi-x-lg me-2"></i>Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
