<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
	use HasFactory;

	protected $fillable = [
		'organization_id',
		'expense_id',
		'date',
		'account_name',
		'description',
		'currency_id',
		'currency_code',
		'bcy_total',
		'bcy_total_without_tax',
		'total',
		'total_without_tax',
		'is_billable',
		'reference_number',
		'customer_id',
		'customer_name',
		'status',
		'created_time',
		'last_modified_time',
		'expense_type',
		'expense_receipt_name',
		'expense_receipt_type',
		'expense_receipt_file',
		'paid_through_account_name',
		'has_attachment',
	];

	protected $casts = [
		'created_time' => 'datetime',
		'expense_id' => 'string',  // Cast expense_id as a string
	];
}
