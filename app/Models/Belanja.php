<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Belanja extends Model
{
    protected $fillable = ['anggaran_id', 'tanggal', 'keterangan', 'nominal'];

    // Relasi: Belanja ini memotong dana dari Anggaran mana?
    public function anggaran(): BelongsTo
    {
        return $this->belongsTo(Anggaran::class);
    }
}