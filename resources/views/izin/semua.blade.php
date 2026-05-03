@extends('layouts.app')

@section('title', 'Semua Permohonan Izin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-inbox me-2 text-primary"></i>Semua Permohonan Izin</h4>
            <p class="text-muted">Kelola semua permohonan izin siswa</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('izin.semua') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filter Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="{{ request('kelas') }}" placeholder="Contoh: 10A">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('izin.semua') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($izin->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-3">Tidak ada permohonan izin.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Diajukan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izin as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($izin->currentPage() - 1) * $izin->perPage() }}</td>
                                    <td class="fw-semibold">{{ $item->user->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->user->kelas ?? '-' }}</span></td>
                                    <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->alasan, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_badge }}">{{ $item->status_label }}</span>
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('izin.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $izin->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
