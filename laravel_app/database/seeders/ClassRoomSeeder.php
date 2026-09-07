<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing to match exact 8 subjects from image
        ClassRoom::query()->delete();

        $subjects = [
            'Rekayasa Perangkat Lunak',
            'Kerja Praktek',
            'Teknologi Internet of Things',
            'Pemrograman II',
            'Basis Data II',
            'Mobile Programming',
            'Sistem Pendukung Keputusan',
            'Teknik Kompilasi',
        ];

        foreach ($subjects as $subject) {
            ClassRoom::create(['name' => $subject]);
        }
    }
}
