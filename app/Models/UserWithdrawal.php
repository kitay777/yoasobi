<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWithdrawal extends Model
{
    //
    protected $fillable = [
        'user_id',
        'message',
    ];
}
