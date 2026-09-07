<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassRoom;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa')->with('subjects');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('class_rooms.id', $request->subject_id);
            });
        }

        $students = $query->orderBy('name', 'asc')->paginate(10);
        $subjects = ClassRoom::orderBy('name', 'asc')->get();
        
        return view('admin.students.index', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim',
            'prodi' => 'required|string',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:class_rooms,id',
        ]);

        $student = User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'password' => Hash::make('tple018#' . substr($request->nim, -6)),
            'role' => 'siswa',
        ]);

        $student->subjects()->sync($request->subject_ids);

        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $student->id,
            'prodi' => 'required|string',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:class_rooms,id',
        ]);

        $student->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
        ]);

        $student->subjects()->sync($request->subject_ids);

        return back()->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(User $student)
    {
        $student->delete();
        Cache::forget('admin_dashboard_stats');
        return back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Data mahasiswa berhasil diimport.');
    }
}
