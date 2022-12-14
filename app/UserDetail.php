<?php

namespace App;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $guarded = [];

    public function getImagePathAttribute()
    {
        return URL::to('/') . '/assets/images/user/' . $this->image;
    }
}
