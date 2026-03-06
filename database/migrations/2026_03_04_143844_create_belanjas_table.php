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
        Schema::create('belanjas', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel anggaran.
            $table->foreignId('anggaran_id')->constrained('anggarans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->decimal('nominal', 15, 2); // Nominal pengeluaran/realisasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belanjas');
    }
};
