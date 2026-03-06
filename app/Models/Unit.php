<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'plate_number',
        'type',
        'status',
    ];

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}