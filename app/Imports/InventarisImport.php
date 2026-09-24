<?php

namespace App\Imports;

use App\Models\Inventaris;
use App\Models\Lab;
use App\Models\SumberDana;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class InventarisImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    protected $jurusanId;
    protected $inserted = 0;
    protected $rowErrors = [];

    public function __construct($jurusanId)
    {
        $this->jurusanId = $jurusanId;

        // Preserve heading apa adanya (jangan di-slug)
        HeadingRowFormatter::default('none');
    }

    public function model(array $row): ?Inventaris
    {
        // Skip baris kosong
        if (empty($row['no_inventaris']) || empty($row['nama_barang'])) {
            return null;
        }

        // Cari Lab by nama + jurusan
        $lab = Lab::where('jurusan_id', $this->jurusanId)
            ->where('nama_lab', trim($row['lab']))
            ->first();

        if (!$lab) {
            throw new \Exception("Lab '{$row['lab']}' nggak ditemukan di jurusan ini.");
        }

        // Cari Sumber Dana by nama + jurusan
        $sumberDana = SumberDana::where('jurusan_id', $this->jurusanId)
            ->where('nama', trim($row['sumber_dana']))
            ->first();

        if (!$sumberDana) {
            throw new \Exception("Sumber Dana '{$row['sumber_dana']}' nggak ditemukan.");
        }

        // Cek duplikat no_inventaris (per jurusan)
        $exists = Inventaris::withoutGlobalScope('jurusan')
            ->where('jurusan_id', $this->jurusanId)
            ->where('no_inventaris', trim($row['no_inventaris']))
            ->exists();

        if ($exists) {
            throw new \Exception("No Inventaris '{$row['no_inventaris']}' udah ada.");
        }

        $this->inserted++;

        return new Inventaris([
            'jurusan_id'      => $this->jurusanId,
            'no_inventaris'   => trim($row['no_inventaris']),
            'tanggal'         => $this->parseDate($row['tanggal']),
            'nama_barang'     => trim($row['nama_barang']),
            'spesifikasi'     => !empty($row['spesifikasi']) ? trim($row['spesifikasi']) : null,
            'volume'          => (int) $row['volume'],
            'satuan'          => trim($row['satuan']),
            'tahun_pembelian' => (int) $row['tahun_pembelian'],
            'harga_satuan'    => $this->parseNumber($row['harga_satuan']),
            'kondisi'         => ucfirst(strtolower(trim($row['kondisi']))),
            'keterangan'      => !empty($row['keterangan']) ? trim($row['keterangan']) : null,
            'lab_id'          => $lab->id,
            'sumber_dana_id'  => $sumberDana->id,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.no_inventaris'   => 'required|string|max:255',
            '*.tanggal'         => 'required',
            '*.nama_barang'     => 'required|string|max:255',
            '*.volume'          => 'required|numeric|min:1',
            '*.satuan'          => 'required|string|max:50',
            '*.tahun_pembelian' => 'required|numeric|min:2000|max:' . (date('Y') + 1),
            '*.harga_satuan'    => 'required|numeric|min:0',
            '*.kondisi'         => 'required|in:Baik,Rusak,baik,rusak',
            '*.lab'             => 'required|string',
            '*.sumber_dana'     => 'required|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.no_inventaris.required'   => 'Kolom "no_inventaris" wajib diisi.',
            '*.tanggal.required'         => 'Kolom "tanggal" wajib diisi.',
            '*.nama_barang.required'     => 'Kolom "nama_barang" wajib diisi.',
            '*.volume.required'          => 'Kolom "volume" wajib diisi.',
            '*.volume.numeric'           => 'Kolom "volume" harus angka.',
            '*.volume.min'               => 'Kolom "volume" minimal 1.',
            '*.satuan.required'          => 'Kolom "satuan" wajib diisi.',
            '*.tahun_pembelian.required' => 'Kolom "tahun_pembelian" wajib diisi.',
            '*.tahun_pembelian.numeric'  => 'Kolom "tahun_pembelian" harus angka.',
            '*.harga_satuan.required'    => 'Kolom "harga_satuan" wajib diisi.',
            '*.harga_satuan.numeric'     => 'Kolom "harga_satuan" harus angka.',
            '*.kondisi.required'         => 'Kolom "kondisi" wajib diisi.',
            '*.kondisi.in'               => 'Kolom "kondisi" harus "Baik" atau "Rusak".',
            '*.lab.required'             => 'Kolom "lab" wajib diisi.',
            '*.sumber_dana.required'     => 'Kolom "sumber_dana" wajib diisi.',
        ];
    }

    public function onError(\Throwable $e): void
    {
        $this->rowErrors[] = $e->getMessage();
    }

    public function getInserted(): int
    {
        return $this->inserted;
    }

    public function getRowErrors(): array
    {
        return $this->rowErrors;
    }

    /**
     * Parse tanggal dengan berbagai format.
     */
    private function parseDate($value): string
    {
        // Excel serial number
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                // fallback ke bawah
            }
        }

        // DateTime object
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        $value = trim((string) $value);

        // dd/mm/yyyy
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }

        // dd-mm-yyyy
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }

        // yyyy-mm-dd
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        }

        // Fallback Carbon
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Parse angka: "Rp 1.500.000" -> 1500000
     */
    private function parseNumber($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $clean = preg_replace('/[^0-9.,]/', '', (string) $value);

        // Format Indonesia: 1.500.000,50
        if (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, ',') !== false) {
            $clean = str_replace(',', '.', $clean);
        }

        return (float) $clean;
    }
}
