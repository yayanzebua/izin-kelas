<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = ClassRoom::orderBy('name', 'asc')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_rooms,name',
        ]);

        ClassRoom::create([
            'name' => $request->name,
        ]);


        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, ClassRoom $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:class_rooms,name,' . $subject->id,
        ]);

        $subject->update([
            'name' => $request->name,
        ]);



        return back()->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(ClassRoom $subject)
    {
        // Check if there are students in this subject
        if ($subject->students()->exists()) {
            return back()->with('error', 'Mata kuliah tidak bisa dihapus karena masih memiliki mahasiswa.');
        }

        $subject->delete();

        Cache::forget('admin_dashboard_stats');
        return back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
