<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{

    protected $fillable = [

        'name',

        'description',

        'price',

        'currency',

        'duration_days',

        'status',

        'features',

    ];


    protected function casts(): array
    {
        return [

            'price' => 'decimal:2',

            'features' => 'array',

        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            Subscription::class
        );
    }

}