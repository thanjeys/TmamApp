<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'account_id',
        'account_name',
        'account_code',
        'account_type',
        'is_user_created',
        'is_system_account',
        'is_standalone_account',
        'is_active',
        'created_time',
        'last_modified_time',
    ];
}
