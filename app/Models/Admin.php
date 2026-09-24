<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
        'created_by',

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




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }




    public function getJWTCustomClaims(): array
    {
        return [

            'type'=>'admin',

        ];
    }




    public function activityLogs(): HasMany
    {

        return $this->hasMany(
            AdminActivityLog::class
        );

    }




    public function permissions(): BelongsToMany
    {

        return $this->belongsToMany(
            Permission::class,
            'admin_permissions'
        );

    }




    public function creator(): BelongsTo
    {

        return $this->belongsTo(
            Admin::class,
            'created_by'
        );

    }




    public function createdAdmins(): HasMany
    {

        return $this->hasMany(
            Admin::class,
            'created_by'
        );

    }


}