<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'image',
        'role',
        'is_active',
        'is_banned'
    ];

    protected $appends = ['image_url'];
    // In your User model
    protected $casts = [
    'is_banned' => 'boolean',
];

// In App\Models\User.php
public function getImageUrlAttribute()
{
    if ($this->image) {
        // Check if image is already a full URL (for social login avatars)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // For locally stored images
        return Storage::disk('public')->url($this->image);
    }

    // Default avatar if no image is set
    return asset('images/default-avatar.png');
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function maintenanceNotifications()
    {
        return $this->belongsToMany(MaintenanceNotification::class)
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }

    public function borrowedDevices(): HasMany
    {
        return $this->hasMany(BorrowDevice::class);
    }

    public function approvedBorrowings(): HasMany
    {
        return $this->hasMany(BorrowDevice::class, 'approved_by');
    }
}
