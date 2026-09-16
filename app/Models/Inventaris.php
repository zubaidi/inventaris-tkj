<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'tanggal',
        'no_inventaris',
        'nama_barang',
        'spesifikasi',
        'volume',
        'satuan',
        'tahun_pembelian',
        'harga_satuan',
        'jumlah_total',
        'kondisi',
        'keterangan',
        'lab_id',
        'sumber_dana_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'volume' => 'integer',
        'tahun_pembelian' => 'integer',
        'harga_satuan' => 'decimal:2',
        'jumlah_total' => 'decimal:2',
    ];

    /**
     * Auto-hitung jumlah_total = volume * harga_satuan.
     * Dijalankan otomatis setiap kali data disimpan (create/update).
     */
    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->jumlah_total = $item->volume * $item->harga_satuan;
        });
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class);
    }

    public function scopeKondisi($query, $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_pembelian', $tahun);
    }

    /**
     * Scope: search nama barang / no inventaris / spesifikasi.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_barang', 'like', "%{$keyword}%")
                ->orWhere('no_inventaris', 'like', "%{$keyword}%")
                ->orWhere('spesifikasi', 'like', "%{$keyword}%");
        });
    }

    /**
     * Accessor: format harga satuan ke Rupiah.
     * Pemakaian: $barang->harga_satuan_rupiah
     */
    public function getHargaSatuanRupiahAttribute()
    {
        return 'Rp '.number_format(floatval($this->harga_satuan), 0, ',', '.');
    }

    /**
     * Accessor: format jumlah total ke Rupiah.
     */
    public function getJumlahTotalRupiahAttribute()
    {
        return 'Rp '.number_format(floatval($this->jumlah_total), 0, ',', '.');
    }
}
