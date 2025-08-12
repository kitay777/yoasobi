<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // 必要なカラムのみmass assignment許可
    protected $fillable = [
        'cast_id',
        'advisor_id',
        'price',
        'start_date',
        'end_date',
        'status',
        'cancel_reason',
    ];

    // 日付型のカラムを自動的にCarbonにキャスト
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function cast()
    {
        return $this->belongsTo(User::class, 'cast_id');
    }
}
