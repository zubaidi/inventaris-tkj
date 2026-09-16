<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_lab' => 'Lab 1',       'lokasi' => 'Gedung TKJ Lt.1', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 2',       'lokasi' => 'Gedung TKJ Lt.1', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 3',       'lokasi' => 'Gedung TKJ Lt.1', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 4',       'lokasi' => 'Gedung TKJ Lt.1', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 5',       'lokasi' => 'Gedung TKJ Lt.2', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 6',       'lokasi' => 'Gedung TKJ Lt.2', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 7',       'lokasi' => 'Gedung TKJ Lt.2', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Lab 8',       'lokasi' => 'Gedung TKJ Lt.2', 'keterangan' => 'Lab Komputer'],
            ['nama_lab' => 'Bengkel TKJ', 'lokasi' => 'Gedung TKJ Lt.1', 'keterangan' => 'Bengkel praktik TKJ'],
        ];

        foreach ($data as $item) {
            DB::table('labs')->updateOrInsert(
                ['nama_lab' => $item['nama_lab']],   // cek berdasarkan nama_lab
                [
                    'lokasi'     => $item['lokasi'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
