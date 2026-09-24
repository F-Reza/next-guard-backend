<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable implements JWTSubject
{

    use Notifiable;


    protected $fillable = [

        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',

    ];



    protected $hidden = [

        'password',

    ];



    protected function casts(): array
    {
        return [

            'password'=>'hashed',

            'last_login_at'=>'datetime',

        ];
    }



    /**
     * JWT identifier
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }



    /**
     * JWT claims
     */
    public function getJWTCustomClaims(): array
    {
        return [

            'type'=>'admin',

        ];
    }


}