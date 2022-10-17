<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];

    public function details(){
        return $this->belongsToMany(User::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function prduct(){
        return $this->belongsTo(Prduct::class);
    }   
}