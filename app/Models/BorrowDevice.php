<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowDevice extends Model
{
    protected $fillable= [
        'device_id',
        'user_id',
        'approved_by',
        'borrowed_at',
        'expected_return_date',
        'returned_at',
        'status',
        'purpose',
        'notes'
    ];
}
