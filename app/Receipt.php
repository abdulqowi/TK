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
            return $this->belongsTo(ProductDetails::class,'product_details_id');
        }
}