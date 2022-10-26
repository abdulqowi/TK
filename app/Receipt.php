<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function course(){
        return $this-> belongsTo(Course::class);   
    }
    public function product(){
        return $this->belongsTo(ProductDetails::class);
    }
    public function getTotalPrice(){
        return ProductDetails::where('id',$this ->product_details_id)->value('total_price');
        return Course::where('id',$this ->course_id)->value('total_price');
    }

}