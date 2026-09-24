<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DeviceProtectionSetting extends Model
{

    protected $fillable = [

        'device_id',

        'betting_block',
        'adult_content_block',
        'facebook_ad_block',
        'youtube_ad_block',
        'safe_search',
        'dns_protection',

        'protection_status',

        'last_sync_at',

    ];


    protected function casts(): array
    {
        return [

            'betting_block' => 'boolean',

            'adult_content_block' => 'boolean',

            'facebook_ad_block' => 'boolean',

            'youtube_ad_block' => 'boolean',

            'safe_search' => 'boolean',

            'dns_protection' => 'boolean',

            'last_sync_at' => 'datetime',

        ];
    }


    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

}