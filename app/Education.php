<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $guarded = [];
    public function user(){
        $this->hasMany(User::class);
    }
}
