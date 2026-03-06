<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggaran extends Model
{
    protected $fillable = ['unit_id', 'tahun', 'nominal'];

    // Relasi: Anggaran ini milik Unit siapa?
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi: Satu Anggaran punya banyak rincian Belanja (Realisasi)
    public function belanjas(): HasMany
    {
        return $this->hasMany(Belanja::class);
    }

    // --- RUMUS OTOMATIS UNTUK DASHBOARD --- //

    // Menghitung total uang yang sudah dibelanjakan (Realisasi)
    public function getTotalRealisasiAttribute()
    {
        return $this->belanjas()->sum('nominal');
    }

    // Menghitung Sisa Anggaran (Anggaran awal - Total Realisasi)
    public function getSisaAnggaranAttribute()
    {
        return $this->nominal - $this->total_realisasi;
    }

    // Menghitung Persentase pemakaian
    public function getPersentaseRealisasiAttribute()
    {
        if ($this->nominal == 0) return 0;
        return round(($this->total_realisasi / $this->nominal) * 100, 1);
    }
}