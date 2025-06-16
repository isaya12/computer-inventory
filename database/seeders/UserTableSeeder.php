<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create admin user
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'image' => 'admin.jpg',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create IT personnel user
        DB::table('users')->insert([
            'first_name' => 'IT',
            'last_name' => 'Personnel',
            'email' => 'it@example.com',
            'phone' => '3456789012',
            'image' => 'it.jpg',
            'password' => Hash::make('password123'),
            'role' => 'it-person',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create staff user
        DB::table('users')->insert([
            'first_name' => 'Staff',
            'last_name' => 'Member',
            'email' => 'staff@example.com',
            'phone' => '4567890123',
            'image' => 'staff.jpg',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
