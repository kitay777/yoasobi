<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'media_type',
        'media_path',
    ];

    // アドバイザー（投稿者）リレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 公開用URL（アクセサ）
    public function getMediaUrlAttribute()
    {
        return $this->media_path ? asset('storage/' . $this->media_path) : null;
    }
}
