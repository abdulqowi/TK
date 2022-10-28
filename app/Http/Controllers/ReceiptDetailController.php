<?php

namespace App\Http\Controllers;

use Exception;
use App\Course;
use App\ReceiptDetail;
use App\ProductDetails;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReceiptDetailController extends Controller
{
    public function index(){
        $receipt = ReceiptDetail::get();
        return apiResponse(200,'success','list',$receipt);
    }

    public function show($id){
        $receipt = ReceiptDetail::findOrFail($id);
        return apiResponse(200,'success','list',$receipt);
    }

    public function store(){
        $rules =[
            'product_details_id' => 'required',
            'quantity' => 'required',
        ];
        $message =[
            'product_details_id.required' => 'Masukan is id produk',
            'quantity' => 'required',
        ];
        $validator = Validator::make($rules, $message);
        $productDetails = ProductDetails::find(request('product_details_id'));
        if ($validator->fails()) {
            return apiResponse(422,'error', $validator->errors()->first());
        }
        try {
            DB::transaction(function ()use($productDetails) {
                ReceiptDetail::create([
                    'user_id' => auth()->user()->id,
                    'product_details_id' => $productDetails->id,
                    'quantity' => request('quantity'),
                    'total_price' => $productDetails-> total_price,
                ]);
            });
            return apiResponse(200,'success','di tambah ke keranjang',$productDetails);

        }catch (exception $e) {
            return apiResponse(400,'error',$e->getMessage());
        }
    }

    

    public function courseStore(){
        $rules =[
            'course_id' =>'required',
        ];
        $message = [
            'course_id.required' => 'Course is required',
        ];
        $validator = Validator::make($rules, $message);
        $course = Course::find(request('course_id'));
        if ($validator->fails()){
            return apiResponse(422,'error', $validator->errors()->first());
        } 
        try {
            DB::transaction(function ()use($course) {
                ReceiptDetail::create([
                    'user_id' => auth()->user()->id,
                    'course_id' => $course->id,
                    'total_price'=>$course->total_price,               
                 ]);
                });
            return apiResponse(200,'success','list:', $course);
        }catch (Exception $e){
            return apiResponse(400,'error',$e->getMessage());
        };
    }
}
