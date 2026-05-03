@extends('layouts.app')

@section('title', 'Izin Saya')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-list-ul me-2 text-primary"></i>Izin Saya</h4>
            <p class="text-muted">Riwayat semua permohonan izin Anda</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('izin.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Izin Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($izin->isEmpty())
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
                                <th>#</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izin as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($izin->currentPage() - 1) * $izin->perPage() }}</td>
                                    <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>{{ Str::limit($item->alasan, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_badge }}">{{ $item->status_label }}</span>
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('izin.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3">
                    {{ $izin->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
