<?php

namespace App\Exports;

use App\Models\Lab;
use App\Models\SumberDana;
use App\Exports\InventarisDataSheet;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventarisTemplateExport implements Export, WithMultipleSheets
{
    protected $jurusanId;

    public function __construct($jurusanId)
    {
        $this->jurusanId = $jurusanId;
    }

    public function sheets(): array
    {
        $labs = Lab::where('jurusan_id', $this->jurusanId)
            ->orderBy('nama_lab')
            ->pluck('nama_lab')
            ->toArray();

        $sumberDanas = SumberDana::where('jurusan_id', $this->jurusanId)
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();

        return [
            new InventarisDataSheet($labs, $sumberDanas),
            new InventarisReferensiSheet($labs, $sumberDanas),
        ];
    }
}
