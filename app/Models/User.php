<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
/*    protected $fillable = [
        'name',
        'email',
        'password',
        'account_name',
        'birthday',
        'region',
        'experience',
        'role',
        'auto_renewal_stopped',
        'paid_plan_expire_at',
    ];
    */



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'location',
    ];

    protected $casts = [
        'share_location' => 'boolean',
        'location_updated_at' => 'datetime',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
             */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'auto_renewal_stopped' => 'boolean',
            'paid_plan_expire_at' => 'date',
            'is_withdrawn' => 'boolean',
            'withdrawn_at' => 'datetime',
        ];
}
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\QueuedVerifyEmail);
    }
    public function advisorProfile()
    {
        return $this->hasOne(AdvisorProfile::class, 'user_id');
    }

    // app/Models/User.php
    public function castSupports()
    {
        return $this->hasMany(CastSupport::class);
    }

    public function subscriptionsAsCast()
    {
        return $this->hasMany(Subscription::class, 'cast_id');
    }

    public function subscriptionsAsAdvisor()
    {
        return $this->hasMany(Subscription::class, 'advisor_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
// User.php
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }



}
