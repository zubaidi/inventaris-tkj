<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InventarisDataSheet implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $labs;
    protected $sumberDanas;
    protected $maxRow = 500;

    public function __construct($labs, $sumberDanas)
    {
        $this->labs        = $labs;
        $this->sumberDanas = $sumberDanas;
    }

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'no_inventaris',
            'tanggal',
            'nama_barang',
            'spesifikasi',
            'volume',
            'satuan',
            'tahun_pembelian',
            'harga_satuan',
            'kondisi',
            'lab',
            'sumber_dana',
            'keterangan',
        ];
    }

    public function title(): string
    {
        return 'Data Inventaris';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, 'B' => 14, 'C' => 25, 'D' => 25,
            'E' => 10, 'F' => 10, 'G' => 16, 'H' => 15,
            'I' => 12, 'J' => 22, 'K' => 20, 'L' => 25,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header style
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Freeze header row
        $sheet->freezePane('A2');

        // Loop baris 2 - maxRow
        for ($row = 2; $row <= $this->maxRow; $row++) {

            // Border tipis
            $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);

            // Dropdown Kondisi (kolom I)
            $this->addDropdown($sheet, "I{$row}", '"Baik,Rusak"', 'Pilih Baik atau Rusak');

            // Dropdown Lab (kolom J)
            if (count($this->labs) > 0) {
                $this->addDropdown(
                    $sheet,
                    "J{$row}",
                    '=Referensi!$A$2:$A$' . (count($this->labs) + 1),
                    'Pilih Lab dari dropdown'
                );
            }

            // Dropdown Sumber Dana (kolom K)
            if (count($this->sumberDanas) > 0) {
                $this->addDropdown(
                    $sheet,
                    "K{$row}",
                    '=Referensi!$B$2:$B$' . (count($this->sumberDanas) + 1),
                    'Pilih Sumber Dana dari dropdown'
                );
            }
        }

        return [];
    }

    private function addDropdown(Worksheet $sheet, string $cell, string $formula, string $errorMsg): void
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowDropDown(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input salah');
        $validation->setError($errorMsg);
        $validation->setFormula1($formula);
    }
}
