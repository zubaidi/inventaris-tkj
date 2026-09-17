<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    protected $table = 'sumber_danas';
     protected $fillable = [
        'nama',
    ];
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }

    /**
     * Accessor: total aset dari sumber dana ini.
     */
    public function getTotalAsetAttribute()
    {
        return $this->inventaris()
            ->selectRaw('SUM(volume * harga_satuan) as total')
            ->value('total') ?? 0;
    }

    public function getTotalAsetRupiahAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_aset, 0, ',', '.');
    }
}
