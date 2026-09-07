<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\ClassRoom;
use App\Exports\LecturerReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $subjects = ClassRoom::orderBy('name', 'asc')->get();
        $query = Permission::with(['user', 'classRoom']);

        if ($request->filled('subject_id')) {
            $query->where('class_room_id', $request->subject_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $reports = $query->latest('date')->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports', 'subjects'));
    }

    public function export(Request $request)
    {
        return Excel::download(new LecturerReportExport($request->all()), 'laporan-dosen-' . date('Y-m-d') . '.xlsx');
    }
}
