<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    protected $guarded= [];
    protected $appends= ['product_list'];

    public function getProductListAttribute(){
        return ProductDetails:: where('id',$this ->product_details_id)->value('product_list');
    }

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
