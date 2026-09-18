<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ============================================
        // LABS
        // ============================================
        Schema::table('labs', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->after('id')
                ->constrained('jurusans')->onDelete('cascade');
        });

        // Drop unique lama kalau ada
        if ($this->indexExists('labs', 'labs_nama_lab_unique')) {
            Schema::table('labs', function (Blueprint $table) {
                $table->dropUnique('labs_nama_lab_unique');
            });
        }

        Schema::table('labs', function (Blueprint $table) {
            $table->unique(['jurusan_id', 'nama_lab'], 'labs_jurusan_nama_unique');
        });

        // ============================================
        // SUMBER DANAS
        // ============================================
        Schema::table('sumber_danas', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->after('id')
                ->constrained('jurusans')->onDelete('cascade');
        });

        if ($this->indexExists('sumber_danas', 'sumber_danas_nama_unique')) {
            Schema::table('sumber_danas', function (Blueprint $table) {
                $table->dropUnique('sumber_danas_nama_unique');
            });
        }

        Schema::table('sumber_danas', function (Blueprint $table) {
            $table->unique(['jurusan_id', 'nama'], 'sumber_danas_jurusan_nama_unique');
        });

        // ============================================
        // INVENTARIS
        // ============================================
        Schema::table('inventaris', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->after('id')
                ->constrained('jurusans')->onDelete('cascade');
        });

        if ($this->indexExists('inventaris', 'inventaris_no_inventaris_unique')) {
            Schema::table('inventaris', function (Blueprint $table) {
                $table->dropUnique('inventaris_no_inventaris_unique');
            });
        }

        Schema::table('inventaris', function (Blueprint $table) {
            $table->unique(['jurusan_id', 'no_inventaris'], 'inventaris_jurusan_no_unique');
        });
    }

    public function down(): void
    {
        // INVENTARIS
        Schema::table('inventaris', function (Blueprint $table) {
            if ($this->indexExists('inventaris', 'inventaris_jurusan_no_unique')) {
                $table->dropUnique('inventaris_jurusan_no_unique');
            }
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn('jurusan_id');
        });

        // SUMBER DANAS
        Schema::table('sumber_danas', function (Blueprint $table) {
            if ($this->indexExists('sumber_danas', 'sumber_danas_jurusan_nama_unique')) {
                $table->dropUnique('sumber_danas_jurusan_nama_unique');
            }
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn('jurusan_id');
        });

        // LABS
        Schema::table('labs', function (Blueprint $table) {
            if ($this->indexExists('labs', 'labs_jurusan_nama_unique')) {
                $table->dropUnique('labs_jurusan_nama_unique');
            }
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn('jurusan_id');
        });
    }

    /**
     * Cek apakah index ada di tabel tertentu.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::select('
            SELECT COUNT(*) as count
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = ?
              AND index_name = ?
        ', [$table, $indexName]);

        return ($result[0]->count ?? 0) > 0;
    }
};
