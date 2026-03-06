<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'unit_id',
        'year',
        'total_amount',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
