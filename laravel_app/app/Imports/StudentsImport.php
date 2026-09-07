<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find or skip if NIM exists
        if (User::where('nim', $row['nim'])->exists()) {
            return null;
        }

        // Handle class_room mapping (simple match by name)
        $class = ClassRoom::where('name', $row['kelas'])->first();

        return new User([
            'name'     => $row['nama'],
            'password' => Hash::make('tple018#' . substr($row['nim'], -6)),
            'role'     => 'siswa',
            'nim'      => $row['nim'],
            'prodi'    => $row['prodi'],
            'class_room_id' => $class?->id,
        ]);
    }
}
