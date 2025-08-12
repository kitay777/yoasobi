<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppealTag extends Model
{
    protected $fillable = ['name'];

    public function advisorProfiles()
    {
        return $this->belongsToMany(AdvisorProfile::class);
    }
}

