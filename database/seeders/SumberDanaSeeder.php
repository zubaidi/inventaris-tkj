<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SumberDanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'BOSP'],
            ['nama' => 'BOSDA'],
            ['nama' => 'Komite'],
            ['nama' => 'UP/TeFa'],
        ];

        foreach ($data as $item) {
            DB::table('sumber_danas')->updateOrInsert(
                ['nama' => $item['nama']],           // cek berdasarkan nama
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
