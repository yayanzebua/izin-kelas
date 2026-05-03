<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@izinkelas.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kelas' => null,
        ]);

        User::create([
            'name' => 'Bapak Guru',
            'email' => 'guru@izinkelas.test',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'kelas' => null,
        ]);

        User::create([
            'name' => 'Ahmad Siswa',
            'email' => 'siswa@izinkelas.test',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'kelas' => '10A',
        ]);
    }
}
