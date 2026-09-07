<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::latest()->paginate(10);
        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
        ]);

        Lecturer::create($request->all());

        return back()->with('success', 'Kontak dosen berhasil ditambahkan.');
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
        ]);

        $lecturer->update($request->all());

        return back()->with('success', 'Kontak dosen berhasil diperbarui.');
    }

    public function destroy(Lecturer $lecturer)
    {
        $lecturer->delete();
        return back()->with('success', 'Kontak dosen berhasil dihapus.');
    }
}
