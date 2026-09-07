<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\ClassRoom;
use App\Services\TelegramService;
use App\Exports\PermissionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with(['user', 'classRoom']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $permissions = $query->latest()->paginate(10)->withQueryString();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function export()
    {
        return Excel::download(new PermissionsExport, 'rekap-izin-' . date('Y-m-d') . '.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'date' => [
                'required',
                'date',
                Rule::unique('permissions')->where(function ($query) use ($request) {
                    return $query->where('user_id', Auth::id())
                                 ->where('class_room_id', $request->class_room_id);
                })
            ],
            'type' => 'required|in:sakit,izin',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ], [
            'date.unique' => 'Anda sudah mengajukan izin untuk mata kuliah ini pada tanggal tersebut.'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('permissions', 'public');
        }

        $permission = Permission::create([
            'user_id' => Auth::id(),
            'class_room_id' => $request->class_room_id,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'file' => $filePath,
            'status' => 'approved',
        ]);

        $this->notifyTelegramIfConfigured($permission, 'approved');

        Cache::forget('admin_dashboard_stats');

        return redirect()->route('dashboard')->with('success', 'Izin berhasil dilaporkan.');
    }

    public function updateStatus(Request $request, Permission $permission)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $previousStatus = $permission->status;
        $permission->update(['status' => $request->status]);

        if ($request->status === 'approved' && $previousStatus !== 'approved') {
            $this->notifyTelegramIfConfigured($permission, 'approved');
        }

        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Status izin berhasil diperbarui menjadi ' . $request->status . '.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->file) {
            Storage::disk('public')->delete($permission->file);
        }
        
        $permission->delete();

        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Data izin berhasil dihapus.');
    }

    private function notifyTelegramIfConfigured(Permission $permission, string $status): void
    {
        $permission->loadMissing(['user', 'classRoom']);
        $user = $permission->user;

        if (!$user || !$user->telegram_chat_id) {
            return;
        }

        $className = $permission->classRoom?->name ?? '-';
        $dateText = Carbon::parse($permission->date)->translatedFormat('d F Y');

        $message = "📝 <b>Laporan Izin Berhasil</b>\n\n" .
            "Halo, <b>{$user->name}</b>. Pengajuan izin Anda telah tercatat dalam sistem.\n\n" .
            "📊 <b>Status:</b> <b>" . strtoupper($status) . "</b>\n" .
            "ℹ️ <b>Jenis:</b> " . strtoupper($permission->type) . "\n" .
            "📅 <b>Tanggal:</b> {$dateText}\n" .
            "📚 <b>Mata Kuliah:</b> {$className}\n" .
            "💬 <b>Alasan:</b> <i>{$permission->description}</i>";

        if ($permission->file) {
            $message .= "\n\n🔗 <b>Bukti:</b> <a href='" . url(Storage::url($permission->file)) . "'>Lihat Dokumen</a>";
        }

        \App\Jobs\SendPermissionNotification::dispatch($user->telegram_chat_id, $message);
    }
}
