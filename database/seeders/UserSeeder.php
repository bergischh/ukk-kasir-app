<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Karyawan Giss',
            'email' => 'karyawan@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'employee',
        ]);
    }
}
