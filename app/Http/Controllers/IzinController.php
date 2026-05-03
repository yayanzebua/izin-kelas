<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isGuru() || $user->isAdmin()) {
            return redirect()->route('izin.semua');
        }

        $izin = Izin::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('izin.index', compact('izin'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            return redirect()->route('guru.dashboard')->with('error', 'Hanya siswa yang dapat mengajukan izin.');
        }
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            return redirect()->route('guru.dashboard')->with('error', 'Hanya siswa yang dapat mengajukan izin.');
        }

        $data = $request->validate([
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string', 'min:10', 'max:1000'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['user_id'] = $user->id;
        $data['status'] = 'menunggu';

        Izin::create($data);

        return redirect()->route('izin.index')->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function show(Izin $izin)
    {
        $user = Auth::user();

        if ($user->isSiswa() && $izin->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke izin ini.');
        }

        $izin->load('user', 'approvedBy');

        return view('izin.show', compact('izin'));
    }

    public function semua(Request $request)
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            return redirect()->route('siswa.dashboard');
        }

        $query = Izin::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        $izin = $query->paginate(15);

        return view('izin.semua', compact('izin'));
    }

    public function approve(Request $request, Izin $izin)
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'catatan_guru' => ['nullable', 'string', 'max:500'],
        ]);

        $izin->update([
            'status' => 'disetujui',
            'catatan_guru' => $data['catatan_guru'] ?? null,
            'approved_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Izin berhasil disetujui.');
    }

    public function reject(Request $request, Izin $izin)
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'catatan_guru' => ['required', 'string', 'max:500'],
        ]);

        $izin->update([
            'status' => 'ditolak',
            'catatan_guru' => $data['catatan_guru'],
            'approved_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Izin berhasil ditolak.');
    }
}
