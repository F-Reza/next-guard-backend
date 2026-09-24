<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}