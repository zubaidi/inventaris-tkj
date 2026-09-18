<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'singkatan', 'kepala_jurusan'];

    public function users()       { return $this->hasMany(User::class); }
    public function labs()        { return $this->hasMany(Lab::class); }
    public function sumberDanas() { return $this->hasMany(SumberDana::class); }
    public function inventaris()  { return $this->hasMany(Inventaris::class); }
}
