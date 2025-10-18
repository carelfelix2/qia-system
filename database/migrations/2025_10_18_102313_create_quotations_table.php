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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('sales_person');
            $table->enum('jenis_penawaran', ['Alat baru', 'Re-kalibrasi', 'Perbaikan', 'Sewa Alat']);
            $table->enum('format_layout', ['With total', 'Without total']);
            $table->string('nama_customer');
            $table->text('alamat_customer');
            $table->string('nama_alat');
            $table->string('part_number');
            $table->enum('kategori_harga', ['HARGA INAPROC 2025', 'HARGA RETAIL 2025', 'NON E-KATALOG (CUSTOM)']);
            $table->decimal('harga', 15, 2);
            $table->enum('ppn', ['YA', 'Tidak']);
            $table->decimal('diskon', 5, 2)->nullable();
            $table->string('pembayaran');
            $table->string('pembayaran_other')->nullable();
            $table->string('stok');
            $table->string('stok_other')->nullable();
            $table->text('keterangan_tambahan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
