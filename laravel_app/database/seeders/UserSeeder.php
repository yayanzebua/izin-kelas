<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Keep Admin
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'Admin Ketua Kelas',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Clear existing students to fresh start with friends list
        User::where('role', 'siswa')->delete();

        $class = ClassRoom::first(); // Default to first class for demo

        $friends = [
            ['nim' => '231011402938', 'name' => 'AGUNG PRIBADI'],
            ['nim' => '231011403533', 'name' => 'AHMAD REZA FAHLEVI'],
            ['nim' => '231011403454', 'name' => 'ALFIAN JULIANTO'],
            ['nim' => '231011402959', 'name' => 'ANAND FERDIANSYAH SUSILO'],
            ['nim' => '231011401957', 'name' => 'ANDHIKA VALLERIAN RAMADHANI SANTOSA'],
            ['nim' => '231011401111', 'name' => 'APRILIA DWI MAR\'ATI'],
            ['nim' => '231011402336', 'name' => 'DAFFA ERLISEN'],
            ['nim' => '231011403059', 'name' => 'FAMATI WARUWU'],
            ['nim' => '231011401157', 'name' => 'FIKRI NURDIANSYAH'],
            ['nim' => '231011403024', 'name' => 'FITUS YAYAN OFONAIO ZEBUA'],
            ['nim' => '231011403217', 'name' => 'HERLINA NASTI MANJA'],
            ['nim' => '231011403183', 'name' => 'KEVIN NATANEL PUTERA PRASETYA'],
            ['nim' => '231011400423', 'name' => 'MUHAMAD AKMAL'],
            ['nim' => '231011403002', 'name' => 'MUHAMAD FATUR RIZKY'],
            ['nim' => '231011403680', 'name' => 'MUHAMMAD ABDUL HAFIDZ SUDRAJAT'],
            ['nim' => '231011403624', 'name' => 'MUHAMMAD AHKSAN NUR'],
            ['nim' => '231011401952', 'name' => 'MUHAMMAD REVANZA RIFADIL'],
            ['nim' => '231011403256', 'name' => 'MUTIARA HANDAYANI'],
            ['nim' => '231011401115', 'name' => 'NADYA AZZAHRA'],
            ['nim' => '231011401135', 'name' => 'PAHRUDIN'],
            ['nim' => '231011402211', 'name' => 'RACHEL PUTRI ZULAIKHA YUDHISTIRA'],
            ['nim' => '231011401970', 'name' => 'RAFLI THIO AL HARIFAISYI'],
            ['nim' => '231011402691', 'name' => 'REYVALDY ANUGRAH DECLANO HASIBUAN'],
            ['nim' => '231011401129', 'name' => 'RICO SEPTIAN'],
            ['nim' => '231011401783', 'name' => 'RISKI ADIBTIYANTO'],
            ['nim' => '231011401966', 'name' => 'RIZQ SAIFULLAH'],
            ['nim' => '231011402866', 'name' => 'SEBASTIAN FHAZRI WASONO'],
            ['nim' => '231011402105', 'name' => 'SEPTIADI'],
            ['nim' => '231011403756', 'name' => 'SHAFA SALSABILA'],
            ['nim' => '231011401138', 'name' => 'SITI FAUZIAH'],
            ['nim' => '231011401126', 'name' => 'SULIS SETIA WATI'],
        ];

        $allSubjects = ClassRoom::all();

        foreach ($friends as $friend) {
            // Format password: tple018# + 6 digit terakhir NIM
            $lastSixNim = substr($friend['nim'], -6);
            $password = 'tple018#' . $lastSixNim;

            $user = User::create([
                'name' => $friend['name'],
                'nim' => $friend['nim'],
                'prodi' => 'TI',
                'password' => Hash::make($password),
                'role' => 'siswa',
            ]);

            // Attach every student to every subject
            $user->subjects()->attach($allSubjects->pluck('id'));
        }
    }
}
