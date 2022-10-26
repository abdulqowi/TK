<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Course;
use App\Receipt;
use App\ProductDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller
{
    public function index(){
        $receipt = Receipt::with('user','course','product')->get();
        return apiResponse(200,'success','list',$receipt);
    }

    public function show($id){
        $receipt = Receipt::with('user','course','product')->findOrFail($id);
        return apiResponse(200,'success','list',$receipt);
    }
    public function store(Request $request,$id){
        $rules =[
            'quantity' => 'required',
        ];
        $message = [
            'quantity.required' => 'Quantity is required',
        ];
        $validator = Validator::make($rules, $message);
        $productDetails = ProductDetails::find($id);
        if ($validator->fails()){
            return apiResponse(422,'error', $validator->errors()->first());
        } try {
            DB::transaction(function ()use($request,$productDetails) {
                Receipt::create([
                    'user_id' => auth()->user()->id,
                    'product_details_id' => $productDetails->id,
                    'quantity' => request('quantity'),
                    'total_price' => $productDetails->total_price,

                 ]);
                });
            return apiResponse(200,'success','list:',$request);
        } catch (Exception $e){
            return apiResponse(400,'error',$e->getMessage());
        };
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
                Receipt::create([
                    'user_id' => 1,
                    'course_id' => $course->id,
                    'total_price'=>$course->total_price,               
                 ]);
                });
            return apiResponse(200,'success','list:', $course);
        }catch (Exception $e){
            return apiResponse(400,'error',$e->getMessage());
        };
    }

    public function userBill ($id){
        $receipt = Receipt::with('user','course','product') ->where('user_id',$id)->get(['product_details_id','quantity','course_id',]);
            return apiResponse(200,'success','list:', $receipt);
    } 
    

    
        
    




































































}