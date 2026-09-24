<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialEvent extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'event_type',
        'old_expires_at',
        'new_expires_at',
        'reason',
        'admin_id',
    ];

    protected function casts(): array
    {
        return [
            'old_expires_at' => 'datetime',
            'new_expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    // public function admin(): BelongsTo
    // {
    //     return $this->belongsTo(Admin::class);
    // }
}