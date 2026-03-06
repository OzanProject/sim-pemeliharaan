<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'budget_id',
        'date',
        'description',
        'amount',
        'receipt_path',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
