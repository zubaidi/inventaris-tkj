<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode' => 'TKJ',  'nama' => 'Teknik Komputer dan Jaringan',   'singkatan' => 'TKJ'],
            ['kode' => 'RPL',  'nama' => 'Rekayasa Perangkat Lunak',       'singkatan' => 'RPL'],
            ['kode' => 'TKR',   'nama' => 'Teknik Kendaraan Ringan',       'singkatan' => 'TKR'],
            ['kode' => 'TSM',  'nama' => 'Teknik Sepeda Motor', 'singkatan' => 'TSM'],
            ['kode' => 'DPB', 'nama' => 'Desain dan Produksi Busana', 'singkatan' => 'DPB'],
        ];

        foreach ($data as $item) {
            Jurusan::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
