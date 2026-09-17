<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'labs';
    protected $fillable = [
        'nama_lab',
        'lokasi',
        'keterangan',
    ];
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }

    /**
     * Accessor: total aset lab ini (dihitung dari volume * harga_satuan).
     */
    public function getTotalAsetAttribute()
    {
        return $this->inventaris()
            ->selectRaw('SUM(volume * harga_satuan) as total')
            ->value('total') ?? 0;
    }

    /**
     * Accessor: total aset lab dalam format Rupiah.
     */
    public function getTotalAsetRupiahAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_aset, 0, ',', '.');
    }
}
