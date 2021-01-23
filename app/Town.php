<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

}
