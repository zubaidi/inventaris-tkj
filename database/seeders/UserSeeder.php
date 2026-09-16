<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Lab',
            'email'    => 'admin@smk.sch.id',
            'password' => 'admin123',   // auto-hash via cast
            'role'     => 'admin',
        ]);

        // User biasa
        User::create([
            'name'     => 'User Biasa',
            'email'    => 'user@smk.sch.id',
            'password' => 'user123',    // auto-hash via cast
            'role'     => 'user',
        ]);
    }
}
