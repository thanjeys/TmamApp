<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'currency_id',
        'currency_code',
        'currency_name',
        'currency_symbol',
        'price_precision',
        'currency_format',
        'is_base_currency',
        'exchange_rate',
        'effective_date',
    ];
}
