<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('kua.demo_password');

        User::create([
            'name' => 'Administrator KUA',
            'email' => 'admin@kua.test',
            'password' => $password,
            'role' => Role::Admin,
        ]);

        User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'petugas@kua.test',
            'password' => $password,
            'role' => Role::Petugas,
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'warga@kua.test',
            'password' => $password,
            'role' => Role::Masyarakat,
            'nik' => '3201010101900001',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Melati No. 5, Desa Sukamaju',
        ]);

        $this->call(DemoSeeder::class);
    }
}
