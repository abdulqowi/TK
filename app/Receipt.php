<?php
namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];
    protected $appends=['grand_total'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function course(){
        return $this-> belongsTo(Course::class);   
    }
    public function product(){
        return $this->belongsTo(ProductDetails::class);
    }
    public function getPrice(){
        return ProductDetails::where('id',$this ->product_details_id)->value('total_price');
        return Course::where('id',$this ->course_id)->value('total_price');
    }

    public function receipt_details()
    {
            return $this->hasMany(ReceiptDetail::class);
    }

    public function getGrandTotalAttribute(){
        return DB::table('receipt_details')
            ->select(DB::raw('SUM(total_price * quantity)'))
            ->where('receipt_id',$this->id)
            ->value("SUM(total_price*quantity)");
    }

}