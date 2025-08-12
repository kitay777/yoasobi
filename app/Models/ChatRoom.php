<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Message;
use App\Models\User;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['user_one_id', 'user_two_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public static function between($userId1, $userId2)
    {
        return self::where(function ($q) use ($userId1, $userId2) {
            $q->where('user_one_id', $userId1)->where('user_two_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('user_one_id', $userId2)->where('user_two_id', $userId1);
        })->first();
    }

    public function otherUser($authUserId)
    {
        return $this->user_one_id === $authUserId ? $this->userTwo : $this->userOne;
    }


}

