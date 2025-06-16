<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\NewTicketNotification;
use App\Models\User;

class Tickets extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'subject',
        'attachment',
        'description',
        'status',
        'resolved_at',
        'closed_at',
        'response_time',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($ticket) {
            // Notify admins and IT staff
            $admins = User::whereIn('role', ['admin', 'it-person'])->get();

            foreach ($admins as $admin) {
                $admin->notify(new NewTicketNotification($ticket));
            }

            // Optionally broadcast the event
            event(new \App\Events\NewTicketCreated($ticket));
        });
    }
}
