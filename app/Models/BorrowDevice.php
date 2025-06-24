<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'borrowed_at' => 'datetime',
        'expected_return_date' => 'datetime',
        'returned_at' => 'datetime',
    ];


    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

