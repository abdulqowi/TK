<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    protected $guarded =[];

    public function receipt()
    {
        return $this->belongsToMany(receipt::class);
    }
}

