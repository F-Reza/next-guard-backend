<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProtectionRule extends Model
{

    protected $fillable = [

        'device_id',

        'category',

        'domain',

        'rule_type',

        'status',

        'description',

    ];


    protected function casts(): array
    {
        return [

        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(
            Device::class
        );
    }

}