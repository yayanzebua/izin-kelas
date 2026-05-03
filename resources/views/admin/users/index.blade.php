@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold"><i class="bi bi-people me-2 text-primary"></i>Kelola Pengguna</h4>
            <p class="text-muted">Daftar semua pengguna terdaftar</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-primary">{{ $stats['total_users'] }}</div>
                    <div class="text-muted mt-1">Total Pengguna</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-info">{{ $stats['siswa'] }}</div>
                    <div class="text-muted mt-1">Siswa</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-success">{{ $stats['guru'] }}</div>
                    <div class="text-muted mt-1">Guru</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="display-4 fw-bold text-warning">{{ $stats['total_izin'] }}</div>
                    <div class="text-muted mt-1">Total Izin</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Kelas</th>
                            <th>Total Izin</th>
                            <th>Terdaftar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="fw-semibold">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($u->role === 'guru')
                                        <span class="badge bg-success">Guru</span>
                                    @else
                                        <span class="badge bg-info">Siswa</span>
                                    @endif
                                </td>
                                <td>{{ $u->kelas ?? '-' }}</td>
                                <td>{{ $u->izin_count }}</td>
                                <td>{{ $u->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                              onsubmit="return confirm('Hapus pengguna {{ $u->name }}? Data izin terkait juga akan dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
