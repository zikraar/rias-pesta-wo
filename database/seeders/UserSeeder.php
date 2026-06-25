<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@riaspesta.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'phone' => '08123456789',
            'is_active' => true,
        ]);

        // Admin WO
        User::create([
            'name' => 'Admin Rias Pesta',
            'email' => 'admin@riaspesta.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '08234567890',
            'is_active' => true,
        ]);

        // Customer demo
        User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'customer@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '08198765432',
            'address' => 'Jl. Sudirman No. 10, Pekanbaru',
            'is_active' => true,
        ]);
    }
}