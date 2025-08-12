<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityVerification extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'image_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
