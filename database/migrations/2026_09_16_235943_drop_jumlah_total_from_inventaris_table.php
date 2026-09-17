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
        Schema::table('inventaris', function (Blueprint $table) {
            Schema::table('inventaris', function (Blueprint $table) {
                $table->dropColumn('jumlah_total');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            Schema::table('inventaris', function (Blueprint $table) {
                $table->decimal('jumlah_total', 15, 2)->after('harga_satuan')->default(0);
            });
        });
    }
};
