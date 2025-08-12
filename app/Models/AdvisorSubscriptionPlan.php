<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorSubscriptionPlan extends Model
{
    protected $fillable = ['advisor_profile_id', 'price'];

    public function advisorProfile()
    {
        return $this->belongsTo(AdvisorProfile::class);
    }
}
