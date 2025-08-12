<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_room_id', 'sender_id', 'content', 'read_at'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function room()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }
}

