<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'contact_id',
        'contact_name',
        'company_name',
        'contact_type',
        'status',
        'payment_terms',
        'payment_terms_label',
        'currency_id',
        'currency_code',
        'outstanding_receivable_amount',
        'unused_credits_receivable_amount',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'created_time',
        'last_modified_time',
    ];
}
