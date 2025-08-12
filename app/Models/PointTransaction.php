<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id', 'points', 'amount', 'stripe_session_id', 'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
