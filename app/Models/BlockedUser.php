<?php

// app/Models/BlockedUser.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $fillable = ['user_id', 'blocked_user_id'];
    public function blockedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'blocked_user_id');
    }
}

