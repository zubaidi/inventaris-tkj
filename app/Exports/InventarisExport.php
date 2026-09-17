<?php

namespace App\Exports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class InventarisExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $labId;

    protected $namaLab;

    public function __construct($labId = null, $namaLab = 'Semua Lab')
    {
        $this->labId = $labId;
        $this->namaLab = $namaLab;
    }

    public function collection(): Collection
    {
        $query = Inventaris::with(['lab', 'sumberDana'])
            ->orderBy('lab_id')
            ->orderBy('nama_barang');

        if ($this->labId) {
            $query->where('lab_id', $this->labId);
        }

        return Collection::make($query->get());
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL INPUT',
            'NOMOR INVENTARIS',
            'NAMA BARANG',
            'SPESIFIKASI',
            'VOLUME',
            'SATUAN',
            'TAHUN PEMBELIAN',
            'HARGA SATUAN',
            'JUMLAH TOTAL',
            'KONDISI BAIK',
            'KONDISI TIDAK',
            'SUMBER DANA',
            'KETERANGAN',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->tanggal?->format('d/m/Y') ?? '-',
            $item->no_inventaris,
            $item->nama_barang,
            $item->spesifikasi ?? '-',
            $item->volume,
            $item->satuan,
            $item->tahun_pembelian,
            (float) $item->harga_satuan,
            (float) ($item->volume * $item->harga_satuan),
            $item->kondisi === 'Baik' ? '✓' : '',
            $item->kondisi === 'Rusak' ? '✓' : '',
            $item->sumberDana->nama ?? '-',
            $item->keterangan ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Inventaris '.$this->namaLab;
    }

    public function styles(Worksheet $sheet) : array
    {
        $highestRow = $sheet->getHighestRow();

        // Style header
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border semua cell
        $sheet->getStyle("A1:N{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Format kolom harga (I & J)
        $sheet->getStyle("I2:J{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        return [];
    }
}
