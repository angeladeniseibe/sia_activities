<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 👑 ADMIN (full access)
        User::create([
            'name' => 'Vincent Admin',
            'email' => 'vincent@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // 🧑‍🔧 STAFF (operations access)
        User::create([
            'name' => 'Aldren Staff',
            'email' => 'aldren@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'staff',
        ]);

        // 👤 CUSTOMER (limited access)
        User::create([
            'name' => 'Regular Customer',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
