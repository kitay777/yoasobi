<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'bank_name',
        'bank_code',
        'branch_name',
        'branch_code',
        'account_type',
        'account_number',
        'account_holder_sei',
        'account_holder_mei',
        'invoice_number',
        'is_default',
        'consents',
    ];

    protected $casts = [
        'consents' => 'array',
        'is_default' => 'boolean',
    ];
}
