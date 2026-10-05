<?php

namespace App\Traits;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToJurusan
{
    protected static function bootBelongsToJurusan(): void
    {
        // 👇 GLOBAL SCOPE — auto filter query
        static::addGlobalScope('tenant', function (Builder $query) {
            // Guest / belum login → nggak filter
            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            // Super admin → nggak filter (liat semua)
            if ($user->isSuperAdmin()) {
                return;
            }

            $table = (new static)->getTable();

            // 👇 Pimpinan (kepsek & waka) → liat semua jurusan di sekolahnya
            if ($user->isPimpinan()) {
                if (Schema::hasColumn($table, 'school_id') && $user->school_id) {
                    $query->where('school_id', $user->school_id);
                }

                return;
            }

            // Admin/User biasa → filter jurusan sendiri
            if (Schema::hasColumn($table, 'school_id') && $user->school_id) {
                $query->where('school_id', $user->school_id);
            }

            if (Schema::hasColumn($table, 'jurusan_id') && $user->jurusan_id) {
                $query->where('jurusan_id', $user->jurusan_id);
            }
        });

        // 👇 AUTO-ISI school_id & jurusan_id pas create
        static::creating(function ($model) {
            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            // Super admin & pimpinan → nggak auto-fill (harus manual)
            if ($user->isSuperAdmin() || $user->isPimpinan()) {
                return;
            }

            if (! $model->school_id && $user->school_id) {
                $model->school_id = $user->school_id;
            }

            if (! $model->jurusan_id && $user->jurusan_id) {
                $model->jurusan_id = $user->jurusan_id;
            }
        });
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
