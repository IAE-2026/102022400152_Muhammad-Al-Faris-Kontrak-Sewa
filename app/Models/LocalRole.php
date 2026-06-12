<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalRole extends Model
{
    protected $fillable = [
        'email',
        'name',
        'role',
        'sso_payload',
    ];

    protected $casts = [
        'sso_payload' => 'array',
    ];
}