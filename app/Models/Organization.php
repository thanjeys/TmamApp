<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'contact_name',
        'email',
        'is_default_org',
        'language_code',
        'fiscal_year_start_month',
        'account_created_date',
        'time_zone',
        'is_org_active',
        'currency_id',
        'currency_code',
        'currency_symbol',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
