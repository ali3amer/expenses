<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function cashes()
    {
        return $this->hasMany(Cash::class);
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }
}
