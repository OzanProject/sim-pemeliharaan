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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel units. Jika unit dihapus, anggaran ikut terhapus.
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->year('tahun');
            $table->decimal('nominal', 15, 2); // Simpan angka besar (Miliaran)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
