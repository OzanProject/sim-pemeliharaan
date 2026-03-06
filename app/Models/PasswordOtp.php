<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Scope a query to only include valid (non-expired) OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
