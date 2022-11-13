<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    protected $guarded= [];

    public function receipt(){
        $this->belongsTo(Receipt::class);
    }

    public function user(){
        $this-> belongsTo(User::class,'user_id');
    }
    public function product(){
        $this-> belongsTo(Product::class,'product_id');
    }
}
