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
}
