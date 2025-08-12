<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    //
    protected $fillable = ['user_id', 'amount', 'status', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requests()
    {
        return $this->hasMany(RewardRequest::class);
    }
}
