<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'display_name',
        'value',
        'type',
        'group',
        'order_column',
    ];
}
