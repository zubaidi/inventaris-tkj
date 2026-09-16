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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();$table->date('tanggal');
            $table->string('no_inventaris')->unique();   // SMKSA/TKJ7/2023/RTR/51
            $table->string('nama_barang');               // Router, PC, dll
            $table->string('spesifikasi')->nullable();   // Mikrotik 951, dll
            $table->integer('volume')->default(1);
            $table->string('satuan')->default('Unit');   // Unit, Buah, Set
            $table->year('tahun_pembelian');
            $table->decimal('harga_satuan', 15, 2);      // 1100000.00
            $table->decimal('jumlah_total', 15, 2);      // auto = volume * harga_satuan
            $table->enum('kondisi', ['Baik', 'Rusak'])->default('Baik');
            $table->text('keterangan')->nullable();

            // Foreign Keys
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('sumber_dana_id')->constrained('sumber_danas')->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();

            // Index buat search & filter
            $table->index('tahun_pembelian');
            $table->index('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
