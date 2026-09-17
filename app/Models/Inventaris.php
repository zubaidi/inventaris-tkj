<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes;
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
    ];

    /**
     * Accessor: hitung jumlah_total dari volume * harga_satuan.
     * Nggak disimpen di DB, dihitung on-the-fly.
     * Pemakaian: $barang->jumlah_total
     */
    public function getJumlahTotalAttribute()
    {
        return (float) $this->volume * (float) $this->harga_satuan;
    }
    /**
     * Accessor: format jumlah total ke Rupiah.
     */
    public function getJumlahTotalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_total, 0, ',', '.');
    }

    /**
     * Accessor: format harga satuan ke Rupiah.
     * Pemakaian: $barang->harga_satuan_rupiah
     */
    public function getHargaSatuanRupiahAttribute()
    {
        return 'Rp '.number_format(floatval($this->harga_satuan), 0, ',', '.');
    }
    /** ini relasi ke tabel lain */
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

}
