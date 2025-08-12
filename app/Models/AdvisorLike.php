<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdvisorLike extends Model
{
    protected $fillable = ['user_id', 'advisor_id'];

// app/Models/AdvisorLike.php

public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // advisor側
}

public function cast()
{
    return $this->belongsTo(User::class, 'advisor_id'); // cast側
}
public function advisor()
{
    return $this->belongsTo(User::class, 'advisor_id');
}



}
