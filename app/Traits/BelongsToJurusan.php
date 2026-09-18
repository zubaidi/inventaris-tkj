<?php

namespace App\Traits;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToJurusan
{
    protected static function bootBelongsToJurusan(): void
    {
        // 👇 GLOBAL SCOPE — auto filter query
        static::addGlobalScope('jurusan', function (Builder $query) {
            // Guest / belum login → nggak filter
            if (! auth()->check()) {
                return;
            }

            $user = auth()->user();

            // Super admin → nggak filter (liat semua)
            if ($user->isSuperAdmin()) {
                return;
            }

            // Admin/User biasa → filter jurusan sendiri
            $query->where('jurusan_id', $user->jurusan_id);
        });

        // 👇 AUTO-ISI jurusan_id pas create
        static::creating(function ($model) {
            if (auth()->check() && ! $model->jurusan_id) {
                $user = auth()->user();
                if (! $user->isSuperAdmin()) {
                    $model->jurusan_id = $user->jurusan_id;
                }
            }
        });
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
