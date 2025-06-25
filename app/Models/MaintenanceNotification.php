<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceNotification extends Model
{
    use HasFactory;

protected $fillable = [
    'schedule_id',
    'type',
    'scheduled_at',
    'is_sent',
    'sent_at'
];
    protected $casts = [
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function schedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }
}
