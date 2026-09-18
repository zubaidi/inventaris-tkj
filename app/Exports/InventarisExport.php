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
        $this->labId   = $labId;
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

        $items = $query->get();

        // Group by nama_barang
        $grouped = $items->groupBy('nama_barang')->map(function ($group) {
            return [
                'nama_barang'   => $group->first()->nama_barang,
                'no_inventaris' => $this->formatNoInventaris($group),
                'spesifikasi'   => $group->pluck('spesifikasi')->filter()->unique()->implode(', '),
                'volume'        => $group->sum('volume'),
                'satuan'        => $group->pluck('satuan')->filter()->unique()->implode(', '),
                'tahun'         => $group->pluck('tahun_pembelian')->filter()->unique()->sort()->implode(', '),
                'harga_satuan'  => $group->avg('harga_satuan'),
                'jumlah_total'  => $group->sum(fn ($i) => $i->volume * $i->harga_satuan),
                'kondisi_baik'  => $group->where('kondisi', 'Baik')->sum('volume'),
                'kondisi_rusak' => $group->where('kondisi', 'Rusak')->sum('volume'),
                'sumber_dana'   => $group->pluck('sumberDana.nama')->filter()->unique()->implode(', '),
                'keterangan'    => $group->pluck('keterangan')->filter()->unique()->implode(', '),
            ];
        });

        return Collection::make($grouped->values());
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. INVENTARIS',
            'NAMA BARANG',
            'SPESIFIKASI',
            'VOLUME',
            'SATUAN',
            'TAHUN',
            'HARGA SATUAN',
            'JUMLAH TOTAL',
            'KONDISI (Baik / Rusak)',
            'SUMBER DANA',
            'KETERANGAN',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        $kondisi = [];
        if ($item['kondisi_baik'] > 0)  $kondisi[] = "Baik: {$item['kondisi_baik']}";
        if ($item['kondisi_rusak'] > 0) $kondisi[] = "Rusak: {$item['kondisi_rusak']}";

        return [
            $no,
            $item['no_inventaris'],
            $item['nama_barang'],
            $item['spesifikasi'] ?: '-',
            $item['volume'],
            $item['satuan'] ?: '-',
            $item['tahun'] ?: '-',
            (float) $item['harga_satuan'],
            (float) $item['jumlah_total'],
            implode(' | ', $kondisi) ?: '-',
            $item['sumber_dana'] ?: '-',
            $item['keterangan'] ?: '-',
        ];
    }

    public function title(): string
    {
        return 'Inventaris ' . $this->namaLab;
    }

    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();

        // Style header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Border semua cell
        $sheet->getStyle("A1:L{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Format angka kolom H & I
        $sheet->getStyle("H2:I{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Center alignment kolom tertentu
        $sheet->getStyle("A2:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header row height
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }

    /**
     * Format nomor inventaris jadi range kalau banyak.
     * Contoh: SMKSA/TKJ7/2023/RTR/51 s/d SMKSA/TKJ7/2023/RTR/60
     * Hasil: SMKSA/TKJ7/2023/RTR/51-60
     */
    private function formatNoInventaris($group): string
    {
        $noList = $group->pluck('no_inventaris')->filter()->unique()->sort()->values();

        if ($noList->count() === 0) return '-';
        if ($noList->count() === 1) return $noList->first();

        $first = $noList->first();
        $last  = $noList->last();

        $firstParts = explode('/', $first);
        $lastParts  = explode('/', $last);

        // Cek jumlah part sama & prefix sama
        if (count($firstParts) === count($lastParts)) {
            $firstPrefix = implode('/', array_slice($firstParts, 0, -1));
            $lastPrefix  = implode('/', array_slice($lastParts, 0, -1));

            if ($firstPrefix === $lastPrefix) {
                $firstNum = end($firstParts);
                $lastNum  = end($lastParts);
                return $firstPrefix . '/' . $firstNum . '-' . $lastNum;
            }
        }

        // Fallback: kalau prefix beda, tampilkan max 2 + jumlah lainnya
        if ($noList->count() > 3) {
            return $noList->take(2)->implode(', ') . ', +' . ($noList->count() - 2) . ' lainnya';
        }

        return $noList->implode(', ');
    }
}
