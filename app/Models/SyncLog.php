<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_name',
        'status',
        'records_processed',
        'start_at',
        'completed_at',
        'error_message',
    ];
}
