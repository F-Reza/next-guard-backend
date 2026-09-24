<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtectionRule extends Model
{

    protected $fillable = [

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

}