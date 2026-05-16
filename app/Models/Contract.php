<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contract_number',
        'property_id',
        'tenant_id',
        'status',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit_amount',
        'terms_and_conditions',
        'created_by'
    ];
}