<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceEvent extends Model
{

    public $timestamps = false;


    protected $fillable = [
        'device_id',
        'event',
    ];


    public function device()
    {
        return $this->belongsTo(Device::class);
    }

}