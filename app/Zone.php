<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $guarded = [];

//    protected $hidden = ['pivot'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }


}
