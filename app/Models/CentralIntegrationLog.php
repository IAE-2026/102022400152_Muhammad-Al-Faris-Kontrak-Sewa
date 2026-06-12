<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentralIntegrationLog extends Model
{
    protected $fillable = [
        'activity_name',
        'contract_id',
        'receipt_number',
        'publish_status',
        'payload',
        'response_body',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}