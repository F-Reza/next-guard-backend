<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;




class Admin extends Authenticatable implements JWTSubject
{

    use Notifiable, SoftDeletes;


    protected $fillable = [

        'name',

        'email',

        'password',

        'role',

        'status',

        'force_password_change',

        'failed_login_attempts',

        'locked_until',

        'last_failed_login_at',

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

            'force_password_change'=>'boolean',

            'failed_login_attempts'=>'integer',

            'locked_until'=>'datetime',

            'last_failed_login_at'=>'datetime',

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


    public function sessions(): HasMany
    {

        return $this->hasMany(
            AdminSession::class
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
        )->withTrashed();
    }



    public function notifications(): HasMany
    {
        return $this->hasMany(
            AdminNotification::class
        );
    }


    public function createdAdmins(): HasMany
    {
        return $this->hasMany(
            Admin::class,
            'created_by'
        )->withTrashed();
    }


}