<?php

namespace Database\Seeders;

use App\Models\User;
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
            'email' => 'superadmin@ponpes-smksa.sch.id',
            'password' => 'super123',
            'role' => 'super_admin',
            'jurusan_id' => null,   // ← null = akses semua
        ]);

        User::create([
            'name' => 'Admin TKJ',
            'email' => 'tkj@ponpes-smksa.sch.id',
            'password' => 'tkj123',
            'role' => 'admin',
            'jurusan_id' => 1,      // ← TKJ
        ]);

        User::create([
            'name' => 'User RPL',
            'email' => 'rpl@ponpes-smksa.sch.id',
            'password' => 'rpl123',
            'role' => 'admin',
            'jurusan_id' => 2,      // ← RPL
        ]);
        User::create([
            'name' => 'User TKR',
            'email' => 'tkr@ponpes-smksa.sch.id',
            'password' => 'rpl123',
            'role' => 'admin',
            'jurusan_id' => 3,      // ← TKR
        ]);
        User::create([
            'name' => 'User RPL',
            'email' => 'tsm@ponpes-smksa.sch.id',
            'password' => 'rpl123',
            'role' => 'admin',
            'jurusan_id' => 4,      // ← TSM
        ]);
        User::create([
            'name' => 'User RPL',
            'email' => 'dpb@ponpes-smksa.sch.id',
            'password' => 'rpl123',
            'role' => 'admin',
            'jurusan_id' => 5,      // ← DPB
        ]);
    }
}
