<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CastProfile extends Model
{
    protected $fillable = [
        'user_id', 'name', 'age', 'region', 'experience', 'support_request', 'avatar_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
