<?php

namespace App\Exports;

use App\Models\Permission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermissionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Permission::with(['user', 'classRoom'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Mahasiswa',
            'Mata Kuliah',
            'Tanggal',
            'Jenis',
            'Alasan',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($permission): array
    {
        return [
            $permission->id,
            $permission->user->name,
            $permission->classRoom->name,
            $permission->date,
            $permission->type,
            $permission->description,
            $permission->status,
            $permission->created_at,
        ];
    }
}
