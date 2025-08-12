<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'age',
        'region',
        'tags',
        'bio',
        'avatar_path',
        'cover_path',
        'introduction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->hasOne(AdvisorSubscriptionPlan::class);
    }

    public function appealTags()
    {
        return $this->belongsToMany(AppealTag::class);
    }
}
