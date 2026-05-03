@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Siswa</h4>
            <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Kelas {{ auth()->user()->kelas }}</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('izin.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Izin Baru
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-primary">{{ $stats['total'] }}</div>
                    <div class="text-muted mt-1">Total Izin</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-warning">{{ $stats['menunggu'] }}</div>
                    <div class="text-muted mt-1">Menunggu</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-success">{{ $stats['disetujui'] }}</div>
                    <div class="text-muted mt-1">Disetujui</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-danger">{{ $stats['ditolak'] }}</div>
                    <div class="text-muted mt-1">Ditolak</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Izin Terbaru</h6>
            <a href="{{ route('izin.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            @if($recentIzin->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-3">Belum ada permohonan izin.</p>
                    <a href="{{ route('izin.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Ajukan Izin Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentIzin as $item)
                                <tr>
                                    <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->alasan, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_badge }}">{{ $item->status_label }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('izin.show', $item) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
