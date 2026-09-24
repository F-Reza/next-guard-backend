<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Permission extends Model
{


    protected $fillable = [

        'name',

        'description',

    ];



    public function admins(): BelongsToMany
    {

        return $this->belongsToMany(

            Admin::class,

            'admin_permissions'

        );

    }


}