@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Guru</h4>
            <p class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('izin.semua') }}" class="btn btn-primary">
                <i class="bi bi-inbox me-2"></i>Lihat Semua Izin
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
            <div class="card text-center h-100 border-warning">
                <div class="card-body">
                    <div class="display-4 fw-bold text-warning">{{ $stats['menunggu'] }}</div>
                    <div class="text-muted mt-1">Menunggu Review</div>
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
            <h6 class="mb-0 fw-bold">
                <i class="bi bi-hourglass-split me-2 text-warning"></i>Izin Menunggu Persetujuan
            </h6>
            <a href="{{ route('izin.semua', ['status' => 'menunggu']) }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            @if($pendingIzin->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-check-circle display-4 text-success"></i>
                    <p class="text-muted mt-3">Tidak ada izin yang menunggu persetujuan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingIzin as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->user->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->user->kelas }}</span></td>
                                    <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->alasan, 40) }}</td>
                                    <td>
                                        <a href="{{ route('izin.show', $item) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i>Review
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
