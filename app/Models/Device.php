<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable=[
        'name',
        'model',
        'brand',
        'category_id',
        'serial_number',
        'description',
        'image',
        'purchase_date',
        'status',
        'barcode',
        'assigned_to',
        'location_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
