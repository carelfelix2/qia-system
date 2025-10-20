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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->string('tipe_alat');
            $table->string('merk')->nullable();
            $table->string('part_number')->nullable();
            $table->enum('kategori_harga', ['HARGA INAPROC 2025', 'HARGA RETAIL 2025', 'NON E-KATALOG (CUSTOM)'])->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->enum('ppn', ['YA', 'Tidak'])->nullable();
            $table->decimal('diskon', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
