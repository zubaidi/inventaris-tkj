<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InventarisReferensiSheet implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $labs;
    protected $sumberDanas;

    public function __construct($labs, $sumberDanas)
    {
        $this->labs        = $labs;
        $this->sumberDanas = $sumberDanas;
    }

    public function array(): array
    {
        $rows = [];
        $max  = max(count($this->labs), count($this->sumberDanas));

        for ($i = 0; $i < $max; $i++) {
            $rows[] = [
                $this->labs[$i] ?? '',
                $this->sumberDanas[$i] ?? '',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Daftar Lab', 'Daftar Sumber Dana'];
    }

    public function title(): string
    {
        return 'Referensi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
